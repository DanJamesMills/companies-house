<?php

namespace DanJamesMills\CompaniesHouse\Resources;

use DanJamesMills\CompaniesHouse\Http\Client;

class OfficerAppointments
{
    public function __construct(
        protected readonly Client $client,
        protected readonly string $officerId,
    ) {}

    /**
     * List all company appointments for this officer.
     * GET /officers/{officer_id}/appointments
     */
    public function list(?int $itemsPerPage = null, ?int $startIndex = null): array
    {
        return $this->client->get("/officers/{$this->officerId}/appointments", [
            'items_per_page' => $itemsPerPage,
            'start_index' => $startIndex,
        ]);
    }
}
