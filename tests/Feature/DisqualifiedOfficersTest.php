<?php

use DanJamesMills\CompaniesHouse\Facades\CompaniesHouse;
use Illuminate\Support\Facades\Http;

test('natural disqualifications returns data for an officer', function () {
    Http::fake([
        '*/disqualified-officers/natural/officer1' => Http::response([
            'date_of_birth'  => ['month' => 1, 'year' => 1970],
            'disqualifications' => [['case_identifier' => 'case1']],
        ], 200),
    ]);

    $result = CompaniesHouse::disqualifiedOfficers()->natural('officer1');

    expect($result['disqualifications'])->toHaveCount(1);
});

test('corporate disqualifications returns data for an officer', function () {
    Http::fake([
        '*/disqualified-officers/corporate/corp1' => Http::response([
            'company_number'    => 'corp1',
            'disqualifications' => [['case_identifier' => 'case2']],
        ], 200),
    ]);

    $result = CompaniesHouse::disqualifiedOfficers()->corporate('corp1');

    expect($result['disqualifications'])->toHaveCount(1);
});
