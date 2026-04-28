<?php

namespace DanJamesMills\CompaniesHouse;

use DanJamesMills\CompaniesHouse\Http\Client;
use DanJamesMills\CompaniesHouse\Http\DocumentClient;
use DanJamesMills\CompaniesHouse\Http\StreamClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class CompaniesHouseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/companies-house.php',
            'companies-house'
        );

        $this->app->singleton(
            Client::class,
            fn (Application $app) => new Client(
                apiKey: $this->resolvedApiKey($app),
                baseUrl: $app['config']['companies-house.base_url'],
                timeout: (int) $app['config']['companies-house.timeout'],
                factory: $app->make(HttpFactory::class),
            )
        );

        $this->app->singleton(
            DocumentClient::class,
            fn (Application $app) => new DocumentClient(
                apiKey: $this->resolvedApiKey($app),
                baseUrl: $app['config']['companies-house.document_api_url'],
                timeout: (int) $app['config']['companies-house.timeout'],
                factory: $app->make(HttpFactory::class),
            )
        );

        $this->app->singleton(
            StreamClient::class,
            fn (Application $app) => new StreamClient(
                apiKey: $this->resolvedStreamApiKey($app),
                streamUrl: $app['config']['companies-house.stream_url'],
                connectTimeout: (int) $app['config']['companies-house.stream_connect_timeout'],
            )
        );

        $this->app->singleton(
            CompaniesHouseStreamManager::class,
            fn (Application $app) => new CompaniesHouseStreamManager(
                $app->make(StreamClient::class),
            )
        );

        $this->app->singleton(
            CompaniesHouseManager::class,
            fn (Application $app) => new CompaniesHouseManager(
                $app->make(Client::class),
                $app->make(DocumentClient::class),
            )
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/companies-house.php' => config_path('companies-house.php'),
            ], 'companies-house-config');
        }
    }

    private function resolvedApiKey(Application $app): string
    {
        $key = $app['config']['companies-house.api_key'];

        if (empty($key)) {
            throw new InvalidArgumentException(
                'Companies House API key is not set. Please add COMPANIES_HOUSE_API_KEY to your .env file.'
            );
        }

        return $key;
    }

    private function resolvedStreamApiKey(Application $app): string
    {
        $key = $app['config']['companies-house.stream_api_key'];

        if (empty($key)) {
            throw new InvalidArgumentException(
                'Companies House streaming API key is not set. Please add COMPANIES_HOUSE_STREAM_API_KEY to your .env file. '
                .'Note: streaming keys are registered separately at https://developer.company-information.service.gov.uk/manage-applications'
            );
        }

        return $key;
    }
}
