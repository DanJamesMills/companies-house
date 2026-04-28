<?php

use DanJamesMills\CompaniesHouse\Facades\CompaniesHouse;
use Illuminate\Support\Facades\Http;

test('psc list returns items for a company', function () {
    Http::fake([
        '*/company/12345678/persons-with-significant-control*' => Http::response([
            'items'       => [
                ['name' => 'John Smith', 'natures_of_control' => ['ownership-of-shares-25-to-50-percent']],
            ],
            'total_results' => 1,
        ], 200),
    ]);

    $result = CompaniesHouse::company('12345678')->personsWithSignificantControl()->list();

    expect($result['items'])->toHaveCount(1)
        ->and($result['items'][0]['name'])->toBe('John Smith');
});

test('psc sub-resource methods hit the correct endpoint', function (string $method, string $id, string $expectedPath) {
    Http::fake(['*' => Http::response([], 200)]);

    CompaniesHouse::company('12345678')->personsWithSignificantControl()->{$method}($id);

    Http::assertSent(fn ($request) => str_contains($request->url(), $expectedPath));
})->with([
    ['individual',                      'notif1', 'persons-with-significant-control/individual/notif1'],
    ['individualBeneficialOwner',       'notif1', 'persons-with-significant-control/individual-beneficial-owner/notif1'],
    ['corporateEntity',                 'notif1', 'persons-with-significant-control/corporate-entity/notif1'],
    ['corporateEntityBeneficialOwner',  'notif1', 'persons-with-significant-control/corporate-entity-beneficial-owner/notif1'],
    ['legalPerson',                     'notif1', 'persons-with-significant-control/legal-person/notif1'],
    ['legalPersonBeneficialOwner',      'notif1', 'persons-with-significant-control/legal-person-beneficial-owner/notif1'],
    ['superSecure',                     'sec1',   'persons-with-significant-control/super-secure/sec1'],
    ['superSecureBeneficialOwner',      'sec1',   'persons-with-significant-control/super-secure-beneficial-owner/sec1'],
    ['notifications',                   'psc1',   'persons-with-significant-control/psc1/notifications'],
]);

test('psc list statements returns items', function () {
    Http::fake([
        '*/company/12345678/persons-with-significant-control-statements*' => Http::response([
            'items'       => [['statement' => 'no-individual-or-entity-with-signficant-control']],
            'total_results' => 1,
        ], 200),
    ]);

    $result = CompaniesHouse::company('12345678')->personsWithSignificantControl()->listStatements();

    expect($result['items'])->toHaveCount(1);
});

test('psc get statement returns a specific statement', function () {
    Http::fake([
        '*/company/12345678/persons-with-significant-control-statements/stmt1' => Http::response([
            'statement' => 'no-individual-or-entity-with-signficant-control',
        ], 200),
    ]);

    $result = CompaniesHouse::company('12345678')->personsWithSignificantControl()->getStatement('stmt1');

    expect($result['statement'])->toBe('no-individual-or-entity-with-signficant-control');
});
