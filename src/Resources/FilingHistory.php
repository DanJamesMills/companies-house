<?php

namespace DanJamesMills\CompaniesHouse\Resources;

use DanJamesMills\CompaniesHouse\Http\Client;

class FilingHistory
{
    public function __construct(
        protected readonly Client $client,
        protected readonly string $companyNumber,
    ) {}

    /**
     * List filing history for a company.
     * GET /company/{company_number}/filing-history
     *
     * @param  int|null  $itemsPerPage  Number of results per page
     * @param  int|null  $startIndex  The index of the first result
     * @param  string|null  $category  Filter by category (e.g. "accounts", "confirmation-statement", "incorporation")
     */
    public function list(?int $itemsPerPage = null, ?int $startIndex = null, ?string $category = null): array
    {
        return $this->client->get("/company/{$this->companyNumber}/filing-history", [
            'items_per_page' => $itemsPerPage,
            'start_index' => $startIndex,
            'category' => $category,
        ]);
    }

    /**
     * Get a specific filing history item.
     * GET /company/{company_number}/filing-history/{transaction_id}
     */
    public function get(string $transactionId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/filing-history/{$transactionId}");
    }
}
