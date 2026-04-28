<?php

namespace DanJamesMills\CompaniesHouse\Resources;

use DanJamesMills\CompaniesHouse\Http\Client;

class Charges
{
    public function __construct(
        protected readonly Client $client,
        protected readonly string $companyNumber,
    ) {}

    /**
     * List charges for a company.
     * GET /company/{company_number}/charges
     *
     * @param  int|null  $itemsPerPage  Number of results per page
     * @param  int|null  $startIndex  The index of the first result
     * @param  string|null  $filter  Enum: "part-satisfied" "satisfied" "outstanding"
     */
    public function list(?int $itemsPerPage = null, ?int $startIndex = null, ?string $filter = null): array
    {
        return $this->client->get("/company/{$this->companyNumber}/charges", [
            'items_per_page' => $itemsPerPage,
            'start_index' => $startIndex,
            'filter' => $filter,
        ]);
    }

    /**
     * Get a specific charge for a company.
     * GET /company/{company_number}/charges/{charge_id}
     */
    public function get(string $chargeId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/charges/{$chargeId}");
    }
}
