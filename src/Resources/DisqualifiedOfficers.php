<?php

namespace DanJamesMills\CompaniesHouse\Resources;

use DanJamesMills\CompaniesHouse\Http\Client;

class DisqualifiedOfficers
{
    public function __construct(protected readonly Client $client) {}

    /**
     * Get a natural officer's disqualifications.
     * GET /disqualified-officers/natural/{officer_id}
     */
    public function natural(string $officerId): array
    {
        return $this->client->get("/disqualified-officers/natural/{$officerId}");
    }

    /**
     * Get a corporate officer's disqualifications.
     * GET /disqualified-officers/corporate/{officer_id}
     */
    public function corporate(string $officerId): array
    {
        return $this->client->get("/disqualified-officers/corporate/{$officerId}");
    }
}
