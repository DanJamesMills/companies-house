<?php

namespace DanJamesMills\CompaniesHouse\Resources;

use DanJamesMills\CompaniesHouse\Http\Client;

class Officers
{
    public function __construct(
        protected readonly Client $client,
        protected readonly string $companyNumber,
    ) {}

    /**
     * List officers for a company.
     * GET /company/{company_number}/officers
     *
     * @param  int|null  $itemsPerPage  Number of results per page
     * @param  int|null  $startIndex  The index of the first result
     * @param  string|null  $orderBy  Enum: "appointed_on" "resigned_on" "surname"
     * @param  string|null  $registerType  Enum: "directors" "secretaries" "llp-members"
     * @param  string|null  $registerView  Display register specific information
     */
    public function list(
        ?int $itemsPerPage = null,
        ?int $startIndex = null,
        ?string $orderBy = null,
        ?string $registerType = null,
        ?string $registerView = null,
    ): array {
        return $this->client->get("/company/{$this->companyNumber}/officers", [
            'items_per_page' => $itemsPerPage,
            'start_index' => $startIndex,
            'order_by' => $orderBy,
            'register_type' => $registerType,
            'register_view' => $registerView,
        ]);
    }

    /**
     * Get a company officer appointment.
     * GET /company/{company_number}/appointments/{appointment_id}
     */
    public function get(string $appointmentId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/appointments/{$appointmentId}");
    }
}
