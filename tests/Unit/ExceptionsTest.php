<?php

use DanJamesMills\CompaniesHouse\Exceptions\AuthenticationException;
use DanJamesMills\CompaniesHouse\Exceptions\CompaniesHouseException;
use DanJamesMills\CompaniesHouse\Exceptions\NotFoundException;
use DanJamesMills\CompaniesHouse\Exceptions\RateLimitException;

test('authentication exception has correct status code and mentions api key', function () {
    $e = new AuthenticationException();

    expect($e->getStatusCode())->toBe(401)
        ->and($e->getMessage())->toContain('API key');
});

test('not found exception includes the uri in its message', function () {
    $e = new NotFoundException(uri: '/company/00000000', body: ['message' => 'Company not found.']);

    expect($e->getStatusCode())->toBe(404)
        ->and($e->getMessage())->toContain('/company/00000000');
});

test('rate limit exception exposes the retry after value', function () {
    $e = new RateLimitException(retryAfter: 60);

    expect($e->getStatusCode())->toBe(429)
        ->and($e->getRetryAfter())->toBe(60);
});

test('rate limit exception returns null when no retry after header was provided', function () {
    $e = new RateLimitException(retryAfter: null);

    expect($e->getRetryAfter())->toBeNull();
});

test('base exception stores status code and raw body', function () {
    $body = ['error' => 'service unavailable'];
    $e    = new CompaniesHouseException(message: 'Service unavailable', statusCode: 503, body: $body);

    expect($e->getStatusCode())->toBe(503)
        ->and($e->getBody())->toBe($body);
});

