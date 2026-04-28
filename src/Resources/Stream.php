<?php

namespace DanJamesMills\CompaniesHouse\Resources;

use DanJamesMills\CompaniesHouse\Http\StreamClient;

class Stream
{
    public function __construct(protected readonly StreamClient $client) {}

    /**
     * Stream real-time company profile changes.
     * GET /companies
     *
     * @param  callable(array $event): void  $callback
     */
    public function companies(callable $callback, ?int $timepoint = null): void
    {
        $this->client->stream('/companies', $callback, $timepoint);
    }

    /**
     * Stream real-time filing history changes.
     * GET /filings
     *
     * @param  callable(array $event): void  $callback
     */
    public function filings(callable $callback, ?int $timepoint = null): void
    {
        $this->client->stream('/filings', $callback, $timepoint);
    }

    /**
     * Stream real-time insolvency case changes.
     * GET /insolvency-cases
     *
     * @param  callable(array $event): void  $callback
     */
    public function insolvencyCases(callable $callback, ?int $timepoint = null): void
    {
        $this->client->stream('/insolvency-cases', $callback, $timepoint);
    }

    /**
     * Stream real-time charge (mortgage) changes.
     * GET /charges
     *
     * @param  callable(array $event): void  $callback
     */
    public function charges(callable $callback, ?int $timepoint = null): void
    {
        $this->client->stream('/charges', $callback, $timepoint);
    }

    /**
     * Stream real-time officer appointment changes.
     * GET /officers
     *
     * @param  callable(array $event): void  $callback
     */
    public function officers(callable $callback, ?int $timepoint = null): void
    {
        $this->client->stream('/officers', $callback, $timepoint);
    }

    /**
     * Stream real-time persons with significant control changes.
     * GET /persons-with-significant-control
     *
     * @param  callable(array $event): void  $callback
     */
    public function personsWithSignificantControl(callable $callback, ?int $timepoint = null): void
    {
        $this->client->stream('/persons-with-significant-control', $callback, $timepoint);
    }

    /**
     * Stream real-time disqualified officer changes.
     * GET /disqualified-officers
     *
     * @param  callable(array $event): void  $callback
     */
    public function disqualifiedOfficers(callable $callback, ?int $timepoint = null): void
    {
        $this->client->stream('/disqualified-officers', $callback, $timepoint);
    }

    /**
     * Stream real-time company exemption changes.
     * GET /company-exemptions
     *
     * @param  callable(array $event): void  $callback
     */
    public function companyExemptions(callable $callback, ?int $timepoint = null): void
    {
        $this->client->stream('/company-exemptions', $callback, $timepoint);
    }

    /**
     * Stream real-time PSC statement changes.
     * GET /persons-with-significant-control-statements
     *
     * @param  callable(array $event): void  $callback
     */
    public function pscStatements(callable $callback, ?int $timepoint = null): void
    {
        $this->client->stream('/persons-with-significant-control-statements', $callback, $timepoint);
    }
}
