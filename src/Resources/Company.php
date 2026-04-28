<?php

namespace DanJamesMills\CompaniesHouse\Resources;

use DanJamesMills\CompaniesHouse\Http\Client;

class Company
{
    public function __construct(
        protected readonly Client $client,
        protected readonly string $companyNumber,
    ) {}

    /**
     * Get a company's profile.
     * GET /company/{companyNumber}
     */
    public function profile(): array
    {
        return $this->client->get("/company/{$this->companyNumber}");
    }

    /**
     * Get a company's registered office address.
     * GET /company/{companyNumber}/registered-office-address
     */
    public function registeredOfficeAddress(): array
    {
        return $this->client->get("/company/{$this->companyNumber}/registered-office-address");
    }

    /**
     * Get a company's registers.
     * GET /company/{companyNumber}/registers
     */
    public function registers(): array
    {
        return $this->client->get("/company/{$this->companyNumber}/registers");
    }

    /**
     * Get a company's insolvency details.
     * GET /company/{companyNumber}/insolvency
     */
    public function insolvency(): array
    {
        return $this->client->get("/company/{$this->companyNumber}/insolvency");
    }

    /**
     * Get a company's exemptions.
     * GET /company/{companyNumber}/exemptions
     */
    public function exemptions(): array
    {
        return $this->client->get("/company/{$this->companyNumber}/exemptions");
    }

    /**
     * Get a company's UK establishments.
     * GET /company/{companyNumber}/uk-establishments
     */
    public function ukEstablishments(): array
    {
        return $this->client->get("/company/{$this->companyNumber}/uk-establishments");
    }

    /**
     * Access officers for this company.
     */
    public function officers(): Officers
    {
        return new Officers($this->client, $this->companyNumber);
    }

    /**
     * Access charges for this company.
     */
    public function charges(): Charges
    {
        return new Charges($this->client, $this->companyNumber);
    }

    /**
     * Access filing history for this company.
     */
    public function filingHistory(): FilingHistory
    {
        return new FilingHistory($this->client, $this->companyNumber);
    }

    /**
     * Access persons with significant control for this company.
     */
    public function personsWithSignificantControl(): PersonsWithSignificantControl
    {
        return new PersonsWithSignificantControl($this->client, $this->companyNumber);
    }
}
