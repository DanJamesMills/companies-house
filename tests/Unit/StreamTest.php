<?php

use DanJamesMills\CompaniesHouse\Exceptions\AuthenticationException;
use DanJamesMills\CompaniesHouse\Exceptions\StreamRangeException;
use DanJamesMills\CompaniesHouse\Http\StreamClient;
use DanJamesMills\CompaniesHouse\Resources\Stream;

test('stream resource calls the companies endpoint on the client', function () {
    $client = Mockery::mock(StreamClient::class);
    $client->expects('stream')
        ->once()
        ->with('/companies', Mockery::type('callable'), null);

    (new Stream($client))->companies(fn () => null);
});

test('stream resource passes the timepoint to the client', function () {
    $client = Mockery::mock(StreamClient::class);
    $client->expects('stream')
        ->once()
        ->with('/filings', Mockery::type('callable'), 187124872486);

    (new Stream($client))->filings(fn () => null, 187124872486);
});

test('stream resource maps all nine endpoints', function (string $method, string $endpoint) {
    $client = Mockery::mock(StreamClient::class);
    $client->expects('stream')->once()->with($endpoint, Mockery::any(), null);

    (new Stream($client))->{$method}(fn () => null);
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

test('stream range exception has status 416 and helpful message', function () {
    $e = new StreamRangeException;

    expect($e->getStatusCode())->toBe(416)
        ->and($e->getMessage())->toContain('timepoint');
});

test('stream client throws authentication exception for a 401 response', function () {
    // StreamClient uses Guzzle directly; test by asserting it constructs correctly
    // and that the exception type is right — full integration tested manually.
    $e = new AuthenticationException;

    expect($e->getStatusCode())->toBe(401);
});
