<?php

use DanJamesMills\CompaniesHouse\Facades\CompaniesHouse;
use Illuminate\Support\Facades\Http;

test('officers list returns items for a company', function () {
    Http::fake([
        '*/company/12345678/officers*' => Http::response([
            'items'        => [
                ['name' => 'SMITH, John', 'officer_role' => 'director'],
                ['name' => 'DOE, Jane', 'officer_role' => 'secretary'],
            ],
            'total_results' => 2,
        ], 200),
    ]);

    $result = CompaniesHouse::company('12345678')->officers()->list();

    expect($result['items'])->toHaveCount(2)
        ->and($result['items'][0]['officer_role'])->toBe('director');
});

test('officers list sends pagination and filter params', function () {
    Http::fake([
        '*/company/12345678/officers*' => Http::response(['items' => [], 'total_results' => 0], 200),
    ]);

    CompaniesHouse::company('12345678')->officers()->list(
        itemsPerPage: 10,
        startIndex: 20,
        orderBy: 'surname',
        registerType: 'directors',
    );

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'items_per_page=10')
            && str_contains($request->url(), 'start_index=20')
            && str_contains($request->url(), 'order_by=surname')
            && str_contains($request->url(), 'register_type=directors');
    });
});

test('officers get returns a specific appointment', function () {
    Http::fake([
        '*/company/12345678/appointments/appt99' => Http::response([
            'name'         => 'SMITH, John',
            'officer_role' => 'director',
        ], 200),
    ]);

    $result = CompaniesHouse::company('12345678')->officers()->get('appt99');

    expect($result['name'])->toBe('SMITH, John');
});
