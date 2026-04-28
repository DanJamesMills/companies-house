<?php

use DanJamesMills\CompaniesHouse\Facades\CompaniesHouse;
use Illuminate\Support\Facades\Http;

test('officer appointments list returns total results', function () {
    Http::fake([
        '*/officers/abc123/appointments*' => Http::response([
            'items' => [['appointed_to' => ['company_number' => '12345678']]],
            'total_results' => 1,
        ], 200),
    ]);

    $result = CompaniesHouse::officer('abc123')->list();

    expect($result['total_results'])->toBe(1)
        ->and($result['items'])->toHaveCount(1);
});

test('officer appointments list sends pagination params', function () {
    Http::fake([
        '*/officers/abc123/appointments*' => Http::response(['items' => [], 'total_results' => 0], 200),
    ]);

    CompaniesHouse::officer('abc123')->list(itemsPerPage: 10, startIndex: 20);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'items_per_page=10')
            && str_contains($request->url(), 'start_index=20');
    });
});
