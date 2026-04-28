<?php

namespace DanJamesMills\CompaniesHouse;

use DanJamesMills\CompaniesHouse\Http\Client;
use DanJamesMills\CompaniesHouse\Http\DocumentClient;
use DanJamesMills\CompaniesHouse\Resources\Company;
use DanJamesMills\CompaniesHouse\Resources\DisqualifiedOfficers;
use DanJamesMills\CompaniesHouse\Resources\Documents;
use DanJamesMills\CompaniesHouse\Resources\OfficerAppointments;
use DanJamesMills\CompaniesHouse\Resources\Search;

class CompaniesHouseManager
{
    public function __construct(
        protected readonly Client $client,
        protected readonly DocumentClient $documentClient,
    ) {}

    /**
     * Access company-related endpoints.
     *
     * Example:
     *   CompaniesHouse::company('12345678')->profile();
     *   CompaniesHouse::company('12345678')->officers()->list();
     */
    public function company(string $companyNumber): Company
    {
        return new Company($this->client, $companyNumber);
    }

    /**
     * Access document download endpoints.
     *
     * Pass the document_metadata URL directly from a filing history item.
     *
     * Example:
     *   $pdf = CompaniesHouse::documents()->pdf($item['links']['document_metadata']);
     *   Storage::put('filings/accounts.pdf', $pdf);
     */
    public function documents(): Documents
    {
        return new Documents($this->documentClient);
    }

    /**
     * Access search endpoints.
     *
     * Example:
     *   CompaniesHouse::search()->companies('ACME Ltd');
     *   CompaniesHouse::search()->all('John Smith');
     */
    public function search(): Search
    {
        return new Search($this->client);
    }

    /**
     * Access disqualified officer endpoints.
     *
     * Example:
     *   CompaniesHouse::disqualifiedOfficers()->natural('abc123');
     *   CompaniesHouse::disqualifiedOfficers()->corporate('xyz789');
     */
    public function disqualifiedOfficers(): DisqualifiedOfficers
    {
        return new DisqualifiedOfficers($this->client);
    }

    /**
     * Access appointments for a specific officer across all companies.
     *
     * Example:
     *   CompaniesHouse::officer('abc123')->list();
     */
    public function officer(string $officerId): OfficerAppointments
    {
        return new OfficerAppointments($this->client, $officerId);
    }
}
