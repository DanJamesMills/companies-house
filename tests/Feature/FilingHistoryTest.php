<?php

use DanJamesMills\CompaniesHouse\Facades\CompaniesHouse;
use Illuminate\Support\Facades\Http;

test('filing history list returns items with total count', function () {
    Http::fake([
        '*/company/12345678/filing-history*' => Http::response([
            'items'       => [
                ['type' => 'AA', 'description' => 'accounts-with-accounts-type-small'],
            ],
            'total_count' => 1,
        ], 200),
    ]);

    $result = CompaniesHouse::company('12345678')->filingHistory()->list();

    expect($result['items'])->toHaveCount(1)
        ->and($result['total_count'])->toBe(1);
});

test('filing history list sends the category filter param', function () {
    Http::fake([
        '*/company/12345678/filing-history*' => Http::response(['items' => [], 'total_count' => 0], 200),
    ]);

    CompaniesHouse::company('12345678')->filingHistory()->list(category: 'accounts');

    Http::assertSent(fn ($request) => str_contains($request->url(), 'category=accounts'));
});

test('filing history get returns a specific filing', function () {
    Http::fake([
        '*/company/12345678/filing-history/txn123' => Http::response([
            'transaction_id' => 'txn123',
            'type'           => 'AA',
        ], 200),
    ]);

    $result = CompaniesHouse::company('12345678')->filingHistory()->get('txn123');

    expect($result['transaction_id'])->toBe('txn123');
});
