<?php

namespace DanJamesMills\CompaniesHouse\Http;

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

    public function __construct(
        protected readonly string $apiKey,
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
     * @throws AuthenticationException
     * @throws NotFoundException
     * @throws RateLimitException
     * @throws CompaniesHouseException
     */
    protected function throwIfFailed(Response $response, string $uri): void
    {
        match (true) {
            $response->status() === 401 => throw new AuthenticationException(),
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
}
