<?php

namespace DanJamesMills\CompaniesHouse;

use DanJamesMills\CompaniesHouse\Http\StreamClient;
use DanJamesMills\CompaniesHouse\Resources\Stream;

class CompaniesHouseStreamManager
{
    private Stream $stream;

    public function __construct(StreamClient $client)
    {
        $this->stream = new Stream($client);
    }

    /**
     * Stream real-time company profile changes.
     * GET /companies
     *
     * @param  callable(array $event): void  $callback
     */
    public function companies(callable $callback, ?int $timepoint = null): void
    {
        $this->stream->companies($callback, $timepoint);
    }

    /**
     * Stream real-time filing history changes.
     * GET /filings
     *
     * @param  callable(array $event): void  $callback
     */
    public function filings(callable $callback, ?int $timepoint = null): void
    {
        $this->stream->filings($callback, $timepoint);
    }

    /**
     * Stream real-time insolvency case changes.
     * GET /insolvency-cases
     *
     * @param  callable(array $event): void  $callback
     */
    public function insolvencyCases(callable $callback, ?int $timepoint = null): void
    {
        $this->stream->insolvencyCases($callback, $timepoint);
    }

    /**
     * Stream real-time charge (mortgage) changes.
     * GET /charges
     *
     * @param  callable(array $event): void  $callback
     */
    public function charges(callable $callback, ?int $timepoint = null): void
    {
        $this->stream->charges($callback, $timepoint);
    }

    /**
     * Stream real-time officer appointment changes.
     * GET /officers
     *
     * @param  callable(array $event): void  $callback
     */
    public function officers(callable $callback, ?int $timepoint = null): void
    {
        $this->stream->officers($callback, $timepoint);
    }

    /**
     * Stream real-time persons with significant control changes.
     * GET /persons-with-significant-control
     *
     * @param  callable(array $event): void  $callback
     */
    public function personsWithSignificantControl(callable $callback, ?int $timepoint = null): void
    {
        $this->stream->personsWithSignificantControl($callback, $timepoint);
    }

    /**
     * Stream real-time disqualified officer changes.
     * GET /disqualified-officers
     *
     * @param  callable(array $event): void  $callback
     */
    public function disqualifiedOfficers(callable $callback, ?int $timepoint = null): void
    {
        $this->stream->disqualifiedOfficers($callback, $timepoint);
    }

    /**
     * Stream real-time company exemption changes.
     * GET /company-exemptions
     *
     * @param  callable(array $event): void  $callback
     */
    public function companyExemptions(callable $callback, ?int $timepoint = null): void
    {
        $this->stream->companyExemptions($callback, $timepoint);
    }

    /**
     * Stream real-time PSC statement changes.
     * GET /persons-with-significant-control-statements
     *
     * @param  callable(array $event): void  $callback
     */
    public function pscStatements(callable $callback, ?int $timepoint = null): void
    {
        $this->stream->pscStatements($callback, $timepoint);
    }
}
