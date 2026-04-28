<?php

namespace DanJamesMills\CompaniesHouse\Facades;

use DanJamesMills\CompaniesHouse\CompaniesHouseManager;
use DanJamesMills\CompaniesHouse\Resources\Company;
use DanJamesMills\CompaniesHouse\Resources\DisqualifiedOfficers;
use DanJamesMills\CompaniesHouse\Resources\Documents;
use DanJamesMills\CompaniesHouse\Resources\OfficerAppointments;
use DanJamesMills\CompaniesHouse\Resources\Search;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Company company(string $companyNumber)
 * @method static Search search()
 * @method static Documents documents()
 * @method static DisqualifiedOfficers disqualifiedOfficers()
 * @method static OfficerAppointments officer(string $officerId)
 *
 * @see \DanJamesMills\CompaniesHouse\CompaniesHouseManager
 */
class CompaniesHouse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CompaniesHouseManager::class;
    }
}
