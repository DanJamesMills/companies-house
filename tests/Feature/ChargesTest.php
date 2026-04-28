<?php

use DanJamesMills\CompaniesHouse\Facades\CompaniesHouse;
use Illuminate\Support\Facades\Http;

test('charges list returns items for a company', function () {
    Http::fake([
        '*/company/12345678/charges*' => Http::response([
            'items' => [
                ['charge_number' => 1, 'status' => 'outstanding'],
            ],
            'total_count' => 1,
        ], 200),
    ]);

    $result = CompaniesHouse::company('12345678')->charges()->list();

    expect($result['items'])->toHaveCount(1)
        ->and($result['items'][0]['status'])->toBe('outstanding');
});

test('charges list sends the filter param', function () {
    Http::fake([
        '*/company/12345678/charges*' => Http::response(['items' => [], 'total_count' => 0], 200),
    ]);

    CompaniesHouse::company('12345678')->charges()->list(filter: 'outstanding');

    Http::assertSent(fn ($request) => str_contains($request->url(), 'filter=outstanding'));
});

test('charges get returns a specific charge', function () {
    Http::fake([
        '*/company/12345678/charges/charge99' => Http::response([
            'charge_number' => 99,
            'status' => 'satisfied',
        ], 200),
    ]);

    $result = CompaniesHouse::company('12345678')->charges()->get('charge99');

    expect($result['status'])->toBe('satisfied');
});
