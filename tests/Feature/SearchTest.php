<?php

use DanJamesMills\CompaniesHouse\Facades\CompaniesHouse;
use Illuminate\Support\Facades\Http;

test('search all returns matching items', function () {
    Http::fake([
        '*/search*' => Http::response([
            'items'         => [['company_name' => 'ACME LIMITED']],
            'total_results' => 1,
        ], 200),
    ]);

    $result = CompaniesHouse::search()->all('ACME');

    expect($result['items'])->toHaveCount(1);
});

test('search companies returns matching companies', function () {
    Http::fake([
        '*/search/companies*' => Http::response([
            'items'         => [['company_name' => 'ACME LIMITED', 'company_number' => '12345678']],
            'total_results' => 1,
        ], 200),
    ]);

    $result = CompaniesHouse::search()->companies('ACME');

    expect($result['items'])->toHaveCount(1)
        ->and($result['items'][0]['company_name'])->toBe('ACME LIMITED');
});

test('search companies sends restrictions param', function () {
    Http::fake(['*/search/companies*' => Http::response(['items' => []], 200)]);

    CompaniesHouse::search()->companies('ACME', restrictions: 'actively-trading');

    Http::assertSent(fn ($request) => str_contains($request->url(), 'restrictions=actively-trading'));
});

test('search officers returns matching officers', function () {
    Http::fake([
        '*/search/officers*' => Http::response([
            'items'         => [['name' => 'SMITH, John']],
            'total_results' => 1,
        ], 200),
    ]);

    $result = CompaniesHouse::search()->officers('John Smith');

    expect($result['items'][0]['name'])->toBe('SMITH, John');
});

test('search disqualified officers returns results', function () {
    Http::fake([
        '*/search/disqualified-officers*' => Http::response([
            'items'         => [['name' => 'SMITH, John']],
            'total_results' => 1,
        ], 200),
    ]);

    $result = CompaniesHouse::search()->disqualifiedOfficers('John Smith');

    expect($result['items'])->toHaveCount(1);
});

test('advanced search returns results', function () {
    Http::fake([
        '*/advanced-search/companies*' => Http::response([
            'items'         => [['company_name' => 'ACME LIMITED']],
            'total_results' => 1,
        ], 200),
    ]);

    $result = CompaniesHouse::search()->advanced([
        'company_name_includes' => 'ACME',
        'company_status'        => ['active'],
    ]);

    expect($result['items'])->toHaveCount(1);
});

test('dissolved search returns results', function () {
    Http::fake([
        '*/dissolved-search/companies*' => Http::response([
            'items'         => [['company_name' => 'OLD ACME LTD']],
            'total_results' => 1,
        ], 200),
    ]);

    $result = CompaniesHouse::search()->dissolved('ACME', searchType: 'begins_with');

    expect($result['items'][0]['company_name'])->toBe('OLD ACME LTD');
});

test('alphabetical search returns results', function () {
    Http::fake([
        '*/alphabetical-search/companies*' => Http::response([
            'items' => [['company_name' => 'ACME LIMITED']],
        ], 200),
    ]);

    $result = CompaniesHouse::search()->alphabetical('ACME');

    expect($result['items'])->toHaveCount(1);
});
