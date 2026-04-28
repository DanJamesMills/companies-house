<?php

namespace DanJamesMills\CompaniesHouse\Exceptions;

class NotFoundException extends CompaniesHouseException
{
    public function __construct(string $uri, ?array $body = null)
    {
        $detail = $body['message'] ?? null;
        $message = "No data found for: {$uri} — this resource may not exist for this company.";

        if ($detail) {
            $message .= " API said: {$detail}";
        }

        parent::__construct(message: $message, statusCode: 404, body: $body);
    }
}
