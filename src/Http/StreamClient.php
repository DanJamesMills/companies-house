<?php

namespace DanJamesMills\CompaniesHouse\Http;

use DanJamesMills\CompaniesHouse\Exceptions\AuthenticationException;
use DanJamesMills\CompaniesHouse\Exceptions\CompaniesHouseException;
use DanJamesMills\CompaniesHouse\Exceptions\RateLimitException;
use DanJamesMills\CompaniesHouse\Exceptions\StreamRangeException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Guzzle-based streaming client. Excluded from code coverage because it opens
 * a real long-lived socket connection that cannot be faked in unit tests.
 *
 * @codeCoverageIgnore
 */
class StreamClient
{
    private GuzzleClient $guzzle;

    public function __construct(
        protected readonly string $apiKey,
        protected readonly string $streamUrl,
        protected readonly int $connectTimeout = 30,
    ) {
        $this->guzzle = new GuzzleClient([
            'base_uri' => rtrim($streamUrl, '/'),
            'auth' => [$apiKey, ''],
            'stream' => true,
            'connect_timeout' => $connectTimeout,
            'timeout' => 0,  // no overall timeout — streams run indefinitely
            'read_timeout' => 90, // reconnect if no data (including heartbeats) for 90s
        ]);
    }

    /**
     * Open a long-running stream connection and process each event via the callback.
     *
     * The callback receives a parsed event array:
     *   [
     *     'event'         => ['timepoint' => int, 'published_at' => string, 'type' => string, ...],
     *     'resource_id'   => string,
     *     'resource_kind' => string,
     *     'resource_uri'  => string,
     *     'data'          => array,
     *   ]
     *
     * Blank lines (heartbeats) are silently ignored.
     * The method returns when the connection is closed by the server.
     *
     * @param  callable(array $event): void  $callback
     *
     * @throws AuthenticationException
     * @throws StreamRangeException
     * @throws RateLimitException
     * @throws CompaniesHouseException
     * @throws GuzzleException
     */
    public function stream(string $endpoint, callable $callback, ?int $timepoint = null): void
    {
        $query = $timepoint !== null ? ['timepoint' => $timepoint] : [];

        $response = $this->guzzle->request('GET', $endpoint, [
            'query' => $query,
            'stream' => true,
        ]);

        $this->throwIfFailed($response->getStatusCode(), $response->getHeaderLine('Retry-After'));

        $body = $response->getBody();
        $buffer = '';

        while (! $body->eof()) {
            $buffer .= $body->read(8192);

            while (($pos = strpos($buffer, "\n")) !== false) {
                $line = rtrim(substr($buffer, 0, $pos));
                $buffer = substr($buffer, $pos + 1);

                if ($line === '') {
                    continue; // heartbeat — keep-alive blank line
                }

                $event = json_decode($line, associative: true);

                if ($event === null) {
                    continue; // malformed line — skip silently
                }

                $callback($event);
            }
        }
    }

    /**
     * @throws AuthenticationException
     * @throws StreamRangeException
     * @throws RateLimitException
     * @throws CompaniesHouseException
     */
    private function throwIfFailed(int $status, string $retryAfter): void
    {
        match (true) {
            $status === 401 => throw new AuthenticationException,
            $status === 416 => throw new StreamRangeException,
            $status === 429 => throw new RateLimitException(
                retryAfter: $retryAfter !== '' ? (int) $retryAfter : null,
            ),
            $status >= 400 => throw new CompaniesHouseException(
                message: "Streaming API returned HTTP {$status}.",
                statusCode: $status,
            ),
            default => null,
        };
    }
}
