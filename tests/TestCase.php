<?php

namespace DanJamesMills\CompaniesHouse\Tests;

use DanJamesMills\CompaniesHouse\CompaniesHouseServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [CompaniesHouseServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('companies-house.api_key', 'test-api-key');
        $app['config']->set('companies-house.base_url', 'https://api.company-information.service.gov.uk');
        $app['config']->set('companies-house.document_api_url', 'https://document-api.company-information.service.gov.uk');
        $app['config']->set('companies-house.timeout', 30);
        $app['config']->set('companies-house.stream_api_key', 'test-stream-api-key');
        $app['config']->set('companies-house.stream_url', 'https://stream.companieshouse.gov.uk');
        $app['config']->set('companies-house.stream_connect_timeout', 30);
    }
}
