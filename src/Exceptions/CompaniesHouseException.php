<?php

namespace DanJamesMills\CompaniesHouse\Exceptions;

use Exception;

class CompaniesHouseException extends Exception
{
    public function __construct(
        string $message,
        int $statusCode = 0,
        private readonly ?array $body = null,
    ) {
        parent::__construct($message, $statusCode);
    }

    public function getStatusCode(): int
    {
        return $this->getCode();
    }

    public function getBody(): ?array
    {
        return $this->body;
    }
}
