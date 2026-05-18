<?php

use DanJamesMills\CompaniesHouse\Data\RateLimit;
use DanJamesMills\CompaniesHouse\Facades\CompaniesHouse;
use Illuminate\Support\Facades\Http;

test('rateLimit returns null before any request is made', function () {
    expect(CompaniesHouse::rateLimit())->toBeNull();
});

test('rateLimit is populated from response headers after a successful request', function () {
    Http::fake([
        '*/company/12345678' => Http::response(
            ['company_number' => '12345678', 'company_name' => 'ACME LIMITED'],
            200,
            [
                'X-Ratelimit-Limit'  => '600',
                'X-Ratelimit-Remain' => '597',
                'X-Ratelimit-Reset'  => '1730107751',
                'X-Ratelimit-Window' => '5m',
            ],
        ),
    ]);

    CompaniesHouse::company('12345678')->profile();

    $rateLimit = CompaniesHouse::rateLimit();

    expect($rateLimit)->toBeInstanceOf(RateLimit::class)
        ->and($rateLimit->limit)->toBe(600)
        ->and($rateLimit->remaining)->toBe(597)
        ->and($rateLimit->resetAt)->toBe(1730107751)
        ->and($rateLimit->window)->toBe('5m');
});

test('rateLimit is populated from document client response headers', function () {
    $metadataUrl = 'https://document-api.company-information.service.gov.uk/document/abc123';

    Http::fake([
        '*/document/abc123' => Http::response(
            ['company_number' => '12345678', 'pages' => 2],
            200,
            [
                'X-Ratelimit-Limit'  => '600',
                'X-Ratelimit-Remain' => '500',
                'X-Ratelimit-Reset'  => '1730107751',
                'X-Ratelimit-Window' => '5m',
            ],
        ),
    ]);

    CompaniesHouse::documents()->metadata($metadataUrl);

    $rateLimit = CompaniesHouse::rateLimit();

    expect($rateLimit)->toBeInstanceOf(RateLimit::class)
        ->and($rateLimit->limit)->toBe(600)
        ->and($rateLimit->remaining)->toBe(500);
});

test('rateLimit reflects the most recent request', function () {
    Http::fake([
        '*/company/12345678' => Http::sequence()
            ->push(
                ['company_number' => '12345678'],
                200,
                ['X-Ratelimit-Limit' => '600', 'X-Ratelimit-Remain' => '599', 'X-Ratelimit-Reset' => '1730107751', 'X-Ratelimit-Window' => '5m'],
            )
            ->push(
                ['company_number' => '12345678'],
                200,
                ['X-Ratelimit-Limit' => '600', 'X-Ratelimit-Remain' => '598', 'X-Ratelimit-Reset' => '1730107751', 'X-Ratelimit-Window' => '5m'],
            ),
    ]);

    CompaniesHouse::company('12345678')->profile();
    expect(CompaniesHouse::rateLimit()->remaining)->toBe(599);

    CompaniesHouse::company('12345678')->profile();
    expect(CompaniesHouse::rateLimit()->remaining)->toBe(598);
});

test('rateLimit returns null when response headers are absent', function () {
    Http::fake([
        '*/company/12345678' => Http::response(['company_number' => '12345678'], 200),
    ]);

    CompaniesHouse::company('12345678')->profile();

    expect(CompaniesHouse::rateLimit())->toBeNull();
});

test('secondsUntilReset returns zero for a past reset timestamp', function () {
    $rateLimit = new RateLimit(
        limit: 600,
        remaining: 100,
        resetAt: time() - 60,
        window: '5m',
    );

    expect($rateLimit->secondsUntilReset())->toBe(0);
});

test('secondsUntilReset returns positive value for a future reset timestamp', function () {
    $rateLimit = new RateLimit(
        limit: 600,
        remaining: 100,
        resetAt: time() + 120,
        window: '5m',
    );

    expect($rateLimit->secondsUntilReset())->toBeGreaterThan(0)
        ->and($rateLimit->secondsUntilReset())->toBeLessThanOrEqual(120);
});

test('resetsAt returns a DateTimeImmutable for the reset timestamp', function () {
    $timestamp = 1730107751;

    $rateLimit = new RateLimit(
        limit: 600,
        remaining: 100,
        resetAt: $timestamp,
        window: '5m',
    );

    expect($rateLimit->resetsAt())->toBeInstanceOf(DateTimeImmutable::class)
        ->and($rateLimit->resetsAt()->getTimestamp())->toBe($timestamp);
});

test('RateLimit fromHeaders returns null when any header is missing', function () {
    expect(RateLimit::fromHeaders('600', '', '1730107751', '5m'))->toBeNull()
        ->and(RateLimit::fromHeaders('', '599', '1730107751', '5m'))->toBeNull()
        ->and(RateLimit::fromHeaders('600', '599', '', '5m'))->toBeNull()
        ->and(RateLimit::fromHeaders('600', '599', '1730107751', ''))->toBeNull();
});
