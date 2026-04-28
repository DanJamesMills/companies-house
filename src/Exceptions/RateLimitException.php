<?php

namespace DanJamesMills\CompaniesHouse\Exceptions;

class RateLimitException extends CompaniesHouseException
{
    public function __construct(
        private readonly ?int $retryAfter = null,
    ) {
        parent::__construct(
            message: 'Companies House API rate limit exceeded (600 requests per 5 minutes).',
            statusCode: 429,
        );
    }

    /**
     * Seconds to wait before retrying, if provided in the Retry-After header.
     */
    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
}
