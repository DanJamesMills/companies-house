<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Companies House API Key
    |--------------------------------------------------------------------------
    |
    | Your API key from the Companies House developer portal.
    | https://developer.company-information.service.gov.uk
    |
    */

    'api_key' => env('COMPANIES_HOUSE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Companies House Public Data API.
    |
    */

    'base_url' => env('COMPANIES_HOUSE_BASE_URL', 'https://api.company-information.service.gov.uk'),

    /*
    |--------------------------------------------------------------------------
    | Document API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Companies House Document API (for downloading filings).
    |
    */

    'document_api_url' => env('COMPANIES_HOUSE_DOCUMENT_API_URL', 'https://document-api.company-information.service.gov.uk'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The number of seconds to wait before the request times out.
    |
    */

    'timeout' => env('COMPANIES_HOUSE_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Streaming API Key
    |--------------------------------------------------------------------------
    |
    | A *separate* API key registered specifically as a Streaming API application
    | at the Companies House Developer Hub. REST API keys and streaming API keys
    | are NOT interchangeable.
    | https://developer.company-information.service.gov.uk/manage-applications
    |
    */

    'stream_api_key' => env('COMPANIES_HOUSE_STREAM_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Streaming API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Companies House Streaming API.
    |
    */

    'stream_url' => env('COMPANIES_HOUSE_STREAM_URL', 'https://stream.companieshouse.gov.uk'),

    /*
    |--------------------------------------------------------------------------
    | Streaming API Connect Timeout
    |--------------------------------------------------------------------------
    |
    | Seconds to wait when first establishing a stream connection.
    | Once connected the stream runs indefinitely.
    |
    */

    'stream_connect_timeout' => env('COMPANIES_HOUSE_STREAM_CONNECT_TIMEOUT', 30),

];
