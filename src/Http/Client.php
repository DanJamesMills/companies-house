<?php

namespace DanJamesMills\CompaniesHouse\Http;

use DanJamesMills\CompaniesHouse\Exceptions\AuthenticationException;
use DanJamesMills\CompaniesHouse\Exceptions\CompaniesHouseException;
use DanJamesMills\CompaniesHouse\Exceptions\NotFoundException;
use DanJamesMills\CompaniesHouse\Exceptions\RateLimitException;

class Client extends BaseClient
{
    /**
     * @throws AuthenticationException
     * @throws NotFoundException
     * @throws RateLimitException
     * @throws CompaniesHouseException
     */
    public function get(string $uri, array $query = []): array
    {
        $response = $this->http->get($uri, array_filter($query, fn ($v) => $v !== null));

        $this->throwIfFailed($response, $uri);

        return $response->json() ?? [];
    }
}
