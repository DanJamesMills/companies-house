<?php

namespace DanJamesMills\CompaniesHouse;

use DanJamesMills\CompaniesHouse\Data\RateLimit;
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
        protected Client $client,
        protected DocumentClient $documentClient,
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

    /**
     * Return a new manager instance that authenticates with a different API key.
     * The original (singleton) instance is not modified.
     *
     * Useful for multi-tenant applications where each user has their own key:
     *
     *   CompaniesHouse::withApiKey($user->ch_api_key)->company('09717426')->profile();
     */
    public function withApiKey(string $apiKey): static
    {
        $clone = clone $this;
        $clone->client = $this->client->withApiKey($apiKey);
        $clone->documentClient = $this->documentClient->withApiKey($apiKey);

        return $clone;
    }

    /**
     * Return a new manager instance that routes all requests through a proxy.
     * The original (singleton) instance is not modified.
     *
     *   CompaniesHouse::withProxy('http://proxy.example.com:8080')->company('09717426')->profile();
     */
    public function withProxy(string $proxy): static
    {
        $clone = clone $this;
        $clone->client = $this->client->withProxy($proxy);
        $clone->documentClient = $this->documentClient->withProxy($proxy);

        return $clone;
    }

    /**
     * Rate limit information extracted from the most recent API response headers.
     *
     * Returns null until at least one request has been made.
     *
     * Example:
     *   $profile = CompaniesHouse::company('09717426')->profile();
     *   $limit   = CompaniesHouse::rateLimit();
     *   // $limit->limit      — total requests allowed per window
     *   // $limit->remaining  — requests remaining in this window
     *   // $limit->resetAt    — Unix timestamp when the window resets
     *   // $limit->window     — window duration (e.g. "5m")
     */
    public function rateLimit(): ?RateLimit
    {
        return $this->client->lastRateLimit() ?? $this->documentClient->lastRateLimit();
    }
}
