<?php

use DanJamesMills\CompaniesHouse\CompaniesHouseStreamManager;
use DanJamesMills\CompaniesHouse\Http\StreamClient;

test('stream manager delegates all nine methods to the stream resource', function (string $method, string $endpoint) {
    $client = Mockery::mock(StreamClient::class);
    $client->expects('stream')->once()->with($endpoint, Mockery::type('callable'), null);

    $manager = new CompaniesHouseStreamManager($client);
    $manager->{$method}(fn () => null);
})->with([
    ['companies',                       '/companies'],
    ['filings',                         '/filings'],
    ['insolvencyCases',                 '/insolvency-cases'],
    ['charges',                         '/charges'],
    ['officers',                        '/officers'],
    ['personsWithSignificantControl',   '/persons-with-significant-control'],
    ['disqualifiedOfficers',            '/disqualified-officers'],
    ['companyExemptions',               '/company-exemptions'],
    ['pscStatements',                   '/persons-with-significant-control-statements'],
]);

test('stream manager passes the timepoint through to the stream resource', function () {
    $client = Mockery::mock(StreamClient::class);
    $client->expects('stream')->once()->with('/companies', Mockery::type('callable'), 187124872486);

    $manager = new CompaniesHouseStreamManager($client);
    $manager->companies(fn () => null, 187124872486);
});
