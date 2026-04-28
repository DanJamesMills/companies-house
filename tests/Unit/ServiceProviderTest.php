<?php

use DanJamesMills\CompaniesHouse\CompaniesHouseServiceProvider;
use DanJamesMills\CompaniesHouse\Http\Client;
use DanJamesMills\CompaniesHouse\Http\StreamClient;

test('service provider throws when api key is missing', function () {
    $this->app['config']->set('companies-house.api_key', '');

    // Force re-resolution so the singleton closure runs again
    $this->app->forgetInstance(Client::class);

    $this->app->make(Client::class);
})->throws(InvalidArgumentException::class, 'COMPANIES_HOUSE_API_KEY');

test('service provider throws when stream api key is missing', function () {
    $this->app['config']->set('companies-house.stream_api_key', '');

    $this->app->forgetInstance(StreamClient::class);

    $this->app->make(StreamClient::class);
})->throws(InvalidArgumentException::class, 'COMPANIES_HOUSE_STREAM_API_KEY');
