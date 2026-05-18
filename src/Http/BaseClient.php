<?php

namespace DanJamesMills\CompaniesHouse\Http;

use DanJamesMills\CompaniesHouse\Data\RateLimit;
use DanJamesMills\CompaniesHouse\Exceptions\AuthenticationException;
use DanJamesMills\CompaniesHouse\Exceptions\CompaniesHouseException;
use DanJamesMills\CompaniesHouse\Exceptions\NotFoundException;
use DanJamesMills\CompaniesHouse\Exceptions\RateLimitException;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

abstract class BaseClient
{
    protected PendingRequest $http;

    private ?RateLimit $lastRateLimit = null;

    public function __construct(
        protected string $apiKey,
        protected readonly string $baseUrl,
        protected readonly int $timeout,
        HttpFactory $factory,
    ) {
        $this->http = $factory
            ->withBasicAuth($this->apiKey, '')
            ->baseUrl(rtrim($this->baseUrl, '/'))
            ->timeout($this->timeout)
            ->acceptJson();
    }

    /**
     * The rate limit information extracted from the most recent response headers.
     * Returns null if no response has been received yet or headers were absent.
     */
    public function lastRateLimit(): ?RateLimit
    {
        return $this->lastRateLimit;
    }

    /**
     * @throws AuthenticationException
     * @throws NotFoundException
     * @throws RateLimitException
     * @throws CompaniesHouseException
     */
    protected function throwIfFailed(Response $response, string $uri): void
    {
        $this->captureRateLimitHeaders($response);

        match (true) {
            $response->status() === 401 => throw new AuthenticationException,
            $response->status() === 404 => throw new NotFoundException(uri: $uri, body: $response->json()),
            $response->status() === 429 => throw new RateLimitException(
                retryAfter: ($h = $response->header('Retry-After')) !== '' ? (int) $h : null,
            ),
            $response->failed() => throw new CompaniesHouseException(
                message: $response->json('error', $response->body()),
                statusCode: $response->status(),
                body: $response->json(),
            ),
            default => null,
        };
    }

    /**
     * Ensure cloned instances get their own PendingRequest rather than sharing
     * a reference to the original. This makes withApiKey() and withProxy() safe
     * regardless of how Laravel implements PendingRequest internally.
     */
    public function __clone()
    {
        $this->http = clone $this->http;
    }

    /**
     * Return a new instance that authenticates with a different API key.
     * The original instance is not modified.
     *
     * Useful for multi-tenant applications where different users supply their
     * own Companies House API keys.
     */
    public function withApiKey(string $apiKey): static
    {
        $clone = clone $this;
        $clone->apiKey = $apiKey;
        $clone->http = $clone->http->withBasicAuth($apiKey, '');

        return $clone;
    }

    /**
     * Return a new instance that routes requests through an HTTP/HTTPS proxy.
     * The original instance is not modified.
     *
     * @param  string  $proxy  Proxy URL, e.g. "http://proxy.example.com:8080"
     */
    public function withProxy(string $proxy): static
    {
        $clone = clone $this;
        $clone->http = $clone->http->withOptions(['proxy' => $proxy]);

        return $clone;
    }

    private function captureRateLimitHeaders(Response $response): void
    {
        $rateLimit = RateLimit::fromHeaders(
            limit: $response->header('X-Ratelimit-Limit'),
            remaining: $response->header('X-Ratelimit-Remain'),
            resetAt: $response->header('X-Ratelimit-Reset'),
            window: $response->header('X-Ratelimit-Window'),
        );

        if ($rateLimit !== null) {
            $this->lastRateLimit = $rateLimit;
        }
    }
}
