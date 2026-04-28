<?php

namespace DanJamesMills\CompaniesHouse\Facades;

use DanJamesMills\CompaniesHouse\CompaniesHouseStreamManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void companies(callable $callback, ?int $timepoint = null)
 * @method static void filings(callable $callback, ?int $timepoint = null)
 * @method static void insolvencyCases(callable $callback, ?int $timepoint = null)
 * @method static void charges(callable $callback, ?int $timepoint = null)
 * @method static void officers(callable $callback, ?int $timepoint = null)
 * @method static void personsWithSignificantControl(callable $callback, ?int $timepoint = null)
 * @method static void disqualifiedOfficers(callable $callback, ?int $timepoint = null)
 * @method static void companyExemptions(callable $callback, ?int $timepoint = null)
 * @method static void pscStatements(callable $callback, ?int $timepoint = null)
 *
 * @see \DanJamesMills\CompaniesHouse\CompaniesHouseStreamManager
 */
class CompaniesHouseStream extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CompaniesHouseStreamManager::class;
    }
}
