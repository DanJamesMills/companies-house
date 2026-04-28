<?php

namespace DanJamesMills\CompaniesHouse\Resources;

use DanJamesMills\CompaniesHouse\Http\Client;

class Search
{
    public function __construct(protected readonly Client $client) {}

    /**
     * Search all resources.
     * GET /search
     *
     * @param  string  $query  The query string to search for
     * @param  int|null  $itemsPerPage  Number of results per page (1-100)
     * @param  int|null  $startIndex  The index of the first result
     */
    public function all(string $query, ?int $itemsPerPage = null, ?int $startIndex = null): array
    {
        return $this->client->get('/search', [
            'q' => $query,
            'items_per_page' => $itemsPerPage,
            'start_index' => $startIndex,
        ]);
    }

    /**
     * Search for companies.
     * GET /search/companies
     *
     * @param  string  $query  The query string to search for
     * @param  int|null  $itemsPerPage  Number of results per page (1-100)
     * @param  int|null  $startIndex  The index of the first result
     * @param  string|null  $restrictions  Enum: "actively-trading" "liquidation" "receivership" "administration" "voluntary-arrangement" "converted-closed" "insolvency-proceedings" "registered" "removed" "open" "closed"
     */
    public function companies(string $query, ?int $itemsPerPage = null, ?int $startIndex = null, ?string $restrictions = null): array
    {
        return $this->client->get('/search/companies', [
            'q' => $query,
            'items_per_page' => $itemsPerPage,
            'start_index' => $startIndex,
            'restrictions' => $restrictions,
        ]);
    }

    /**
     * Search for officers.
     * GET /search/officers
     *
     * @param  string  $query  The query string to search for
     * @param  int|null  $itemsPerPage  Number of results per page (1-100)
     * @param  int|null  $startIndex  The index of the first result
     */
    public function officers(string $query, ?int $itemsPerPage = null, ?int $startIndex = null): array
    {
        return $this->client->get('/search/officers', [
            'q' => $query,
            'items_per_page' => $itemsPerPage,
            'start_index' => $startIndex,
        ]);
    }

    /**
     * Search for disqualified officers.
     * GET /search/disqualified-officers
     *
     * @param  string  $query  The query string to search for
     * @param  int|null  $itemsPerPage  Number of results per page (1-100)
     * @param  int|null  $startIndex  The index of the first result
     */
    public function disqualifiedOfficers(string $query, ?int $itemsPerPage = null, ?int $startIndex = null): array
    {
        return $this->client->get('/search/disqualified-officers', [
            'q' => $query,
            'items_per_page' => $itemsPerPage,
            'start_index' => $startIndex,
        ]);
    }

    /**
     * Advanced search for companies.
     * GET /advanced-search/companies
     *
     * @param  array  $params  See Companies House docs for available parameters:
     *                         company_name_includes, company_name_excludes, company_status, sic_codes,
     *                         company_type, company_subtype, dissolved_from, dissolved_to,
     *                         incorporated_from, incorporated_to, location, registered_office_address,
     *                         size, items_per_page, start_index
     */
    public function advanced(array $params = []): array
    {
        return $this->client->get('/advanced-search/companies', $params);
    }

    /**
     * Search for a company alphabetically.
     * GET /alphabetical-search/companies
     *
     * @param  string  $query  The company name to search for
     * @param  string|null  $searchAbove  The company name just above the start of the result set
     * @param  string|null  $searchBelow  The company name just below the end of the result set
     * @param  int|null  $size  Number of results to return (max 100)
     */
    public function alphabetical(string $query, ?string $searchAbove = null, ?string $searchBelow = null, ?int $size = null): array
    {
        return $this->client->get('/alphabetical-search/companies', [
            'q' => $query,
            'search_above' => $searchAbove,
            'search_below' => $searchBelow,
            'size' => $size,
        ]);
    }

    /**
     * Search for dissolved companies.
     * GET /dissolved-search/companies
     *
     * @param  string  $query  The query string to search for
     * @param  string|null  $searchType  Enum: "begins_with" "contains" (default: "begins_with")
     * @param  string|null  $dissolvedFrom  Date a dissolved company was dissolved from (YYYY-MM-DD)
     * @param  string|null  $dissolvedTo  Date a dissolved company was dissolved to (YYYY-MM-DD)
     * @param  string|null  $incorporatedFrom  Date a dissolved company was incorporated from (YYYY-MM-DD)
     * @param  string|null  $incorporatedTo  Date a dissolved company was incorporated to (YYYY-MM-DD)
     * @param  int|null  $size  Number of results to return (max 100)
     * @param  int|null  $startIndex  The index of the first result
     */
    public function dissolved(
        string $query,
        ?string $searchType = null,
        ?string $dissolvedFrom = null,
        ?string $dissolvedTo = null,
        ?string $incorporatedFrom = null,
        ?string $incorporatedTo = null,
        ?int $size = null,
        ?int $startIndex = null,
    ): array {
        return $this->client->get('/dissolved-search/companies', [
            'q' => $query,
            'search_type' => $searchType,
            'dissolved_from' => $dissolvedFrom,
            'dissolved_to' => $dissolvedTo,
            'incorporated_from' => $incorporatedFrom,
            'incorporated_to' => $incorporatedTo,
            'size' => $size,
            'start_index' => $startIndex,
        ]);
    }
}
