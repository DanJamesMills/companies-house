<?php

use DanJamesMills\CompaniesHouse\CompaniesHouseManager;
use DanJamesMills\CompaniesHouse\Facades\CompaniesHouse;
use Illuminate\Support\Facades\Http;

test('withApiKey sends requests with the new api key', function () {
    Http::fake([
        '*/company/12345678*' => Http::response(['company_number' => '12345678'], 200),
    ]);

    CompaniesHouse::withApiKey('new-test-key')->company('12345678')->profile();

    Http::assertSent(function ($request) {
        return $request->header('Authorization')[0] === 'Basic '.base64_encode('new-test-key:');
    });
});

test('withApiKey returns a new manager instance and does not mutate the original', function () {
    $original = app(CompaniesHouseManager::class);
    $derived = CompaniesHouse::withApiKey('another-key');

    expect($derived)->not->toBe($original);
});

test('original manager still uses its own key after withApiKey is called', function () {
    Http::fake([
        '*/company/12345678*' => Http::response(['company_number' => '12345678'], 200),
    ]);

    $originalKey = config('companies-house.api_key');

    CompaniesHouse::withApiKey('override-key')->company('12345678')->profile();
    CompaniesHouse::company('12345678')->profile();

    $requests = Http::recorded();

    expect(base64_decode(
        str_replace('Basic ', '', $requests[0][0]->header('Authorization')[0])
    ))->toBe('override-key:')
        ->and(base64_decode(
            str_replace('Basic ', '', $requests[1][0]->header('Authorization')[0])
        ))->toBe($originalKey.':');
});

test('withProxy returns a new manager instance and does not mutate the original', function () {
    $original = app(CompaniesHouseManager::class);
    $derived = CompaniesHouse::withProxy('http://proxy.example.com:8080');

    expect($derived)->not->toBe($original);
});

test('withProxy makes requests successfully', function () {
    Http::fake([
        '*/company/12345678*' => Http::response(['company_number' => '12345678'], 200),
    ]);

    $result = CompaniesHouse::withProxy('http://proxy.example.com:8080')
        ->company('12345678')
        ->profile();

    expect($result['company_number'])->toBe('12345678');
});

test('withApiKey and withProxy can be chained', function () {
    Http::fake([
        '*/company/12345678*' => Http::response(['company_number' => '12345678'], 200),
    ]);

    $result = CompaniesHouse::withApiKey('chained-key')
        ->withProxy('http://proxy.example.com:8080')
        ->company('12345678')
        ->profile();

    Http::assertSent(function ($request) {
        return $request->header('Authorization')[0] === 'Basic '.base64_encode('chained-key:');
    });

    expect($result['company_number'])->toBe('12345678');
});
