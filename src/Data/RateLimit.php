<?php

namespace DanJamesMills\CompaniesHouse\Data;

use DateTimeImmutable;

class RateLimit
{
    public function __construct(
        /** Total requests allowed in the current window. */
        public readonly int $limit,
        /** Requests remaining in the current window. */
        public readonly int $remaining,
        /** Unix timestamp at which the window resets. */
        public readonly int $resetAt,
        /** Window duration string as returned by the API (e.g. "5m"). */
        public readonly string $window,
    ) {}

    /**
     * Build from the four rate-limit response headers.
     * Returns null when the headers are absent (e.g. on error responses).
     */
    public static function fromHeaders(
        string $limit,
        string $remaining,
        string $resetAt,
        string $window,
    ): ?self {
        if ($limit === '' || $remaining === '' || $resetAt === '' || $window === '') {
            return null;
        }

        return new self(
            limit: (int) $limit,
            remaining: (int) $remaining,
            resetAt: (int) $resetAt,
            window: $window,
        );
    }

    /**
     * Number of seconds until the rate-limit window resets.
     * Returns 0 if the reset time is already in the past.
     */
    public function secondsUntilReset(): int
    {
        return max(0, $this->resetAt - time());
    }

    /**
     * The reset time as a DateTimeImmutable instance.
     */
    public function resetsAt(): DateTimeImmutable
    {
        return (new DateTimeImmutable)->setTimestamp($this->resetAt);
    }
}
