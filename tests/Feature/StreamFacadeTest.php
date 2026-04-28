<?php

use DanJamesMills\CompaniesHouse\CompaniesHouseStreamManager;
use DanJamesMills\CompaniesHouse\Facades\CompaniesHouseStream;

test('companies house stream facade resolves and delegates to the manager', function () {
    $mock = Mockery::mock(CompaniesHouseStreamManager::class);
    $mock->expects('companies')->once()->with(Mockery::type('callable'));

    app()->instance(CompaniesHouseStreamManager::class, $mock);

    CompaniesHouseStream::companies(fn () => null);
});
