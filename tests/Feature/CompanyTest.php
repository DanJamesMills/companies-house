<?php

use DanJamesMills\CompaniesHouse\Exceptions\AuthenticationException;
use DanJamesMills\CompaniesHouse\Exceptions\CompaniesHouseException;
use DanJamesMills\CompaniesHouse\Exceptions\NotFoundException;
use DanJamesMills\CompaniesHouse\Exceptions\RateLimitException;
use DanJamesMills\CompaniesHouse\Facades\CompaniesHouse;
use Illuminate\Support\Facades\Http;

test('company profile returns parsed json', function () {
    Http::fake([
        '*/company/12345678' => Http::response([
            'company_number' => '12345678',
            'company_name' => 'ACME LIMITED',
            'company_status' => 'active',
            'etag' => 'abc123',
        ], 200),
    ]);

    $profile = CompaniesHouse::company('12345678')->profile();

    expect($profile['company_name'])->toBe('ACME LIMITED')
        ->and($profile['company_status'])->toBe('active')
        ->and($profile['etag'])->toBe('abc123');
});

test('registered office address returns address data', function () {
    Http::fake([
        '*/company/12345678/registered-office-address' => Http::response([
            'address_line_1' => '123 Test Street',
            'locality' => 'London',
            'postal_code' => 'EC1A 1BB',
        ], 200),
    ]);

    $address = CompaniesHouse::company('12345678')->registeredOfficeAddress();

    expect($address['postal_code'])->toBe('EC1A 1BB');
});

test('optional company endpoints throw NotFoundException when no data exists', function (string $method) {
    Http::fake([
        '*' => Http::response(['message' => 'resource not found'], 404),
    ]);

    CompaniesHouse::company('12345678')->{$method}();
})->with(['registers', 'insolvency', 'exemptions', 'ukEstablishments'])
    ->throws(NotFoundException::class);

test('company profile throws NotFoundException for a 404 response', function () {
    Http::fake([
        '*/company/00000000' => Http::response(['message' => 'company does not exist'], 404),
    ]);

    CompaniesHouse::company('00000000')->profile();
})->throws(NotFoundException::class);

test('company profile throws AuthenticationException for a 401 response', function () {
    Http::fake([
        '*/company/*' => Http::response(['error' => 'Invalid API key'], 401),
    ]);

    CompaniesHouse::company('12345678')->profile();
})->throws(AuthenticationException::class);

test('company profile throws CompaniesHouseException for a 500 response', function () {
    Http::fake([
        '*/company/*' => Http::response(['error' => 'internal server error'], 500),
    ]);

    CompaniesHouse::company('12345678')->profile();
})->throws(CompaniesHouseException::class);

test('company profile throws RateLimitException and exposes the retry after value', function () {
    Http::fake([
        '*/company/*' => Http::response([], 429, ['Retry-After' => '30']),
    ]);

    $caught = null;

    try {
        CompaniesHouse::company('12345678')->profile();
    } catch (RateLimitException $e) {
        $caught = $e;
    }

    expect($caught)->toBeInstanceOf(RateLimitException::class)
        ->and($caught->getRetryAfter())->toBe(30);
});
