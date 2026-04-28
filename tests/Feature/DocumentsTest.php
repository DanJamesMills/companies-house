<?php

use DanJamesMills\CompaniesHouse\Exceptions\NotFoundException;
use DanJamesMills\CompaniesHouse\Facades\CompaniesHouse;
use Illuminate\Support\Facades\Http;

// The document metadata URL as it appears in filing history links
$metadataUrl = 'https://document-api.company-information.service.gov.uk/document/abc123';

test('metadata returns parsed json for a document', function () use ($metadataUrl) {
    Http::fake([
        '*/document/abc123' => Http::response([
            'company_number' => '12345678',
            'pages'          => 4,
        ], 200),
    ]);

    $result = CompaniesHouse::documents()->metadata($metadataUrl);

    expect($result['pages'])->toBe(4);
});

test('pdf download follows the 302 redirect and returns binary content', function () use ($metadataUrl) {
    Http::fake([
        '*/document/abc123/content' => Http::response('', 302, [
            'Location' => 'https://s3.example.com/filing.pdf',
        ]),
        'https://s3.example.com/filing.pdf' => Http::response('%PDF-1.4 binary content', 200),
    ]);

    $pdf = CompaniesHouse::documents()->pdf($metadataUrl);

    expect($pdf)->toBe('%PDF-1.4 binary content');
});

test('xhtml download follows the 302 redirect and returns xhtml content', function () use ($metadataUrl) {
    Http::fake([
        '*/document/abc123/content' => Http::response('', 302, [
            'Location' => 'https://s3.example.com/filing.xhtml',
        ]),
        'https://s3.example.com/filing.xhtml' => Http::response('<html><body>filing</body></html>', 200),
    ]);

    $xhtml = CompaniesHouse::documents()->xhtml($metadataUrl);

    expect($xhtml)->toContain('<html>');
});

test('metadata throws NotFoundException when document does not exist', function () use ($metadataUrl) {
    Http::fake([
        '*/document/abc123' => Http::response(['message' => 'not found'], 404),
    ]);

    CompaniesHouse::documents()->metadata($metadataUrl);
})->throws(NotFoundException::class);

test('pdf download returns content directly when no redirect is issued', function () use ($metadataUrl) {
    Http::fake([
        '*/document/abc123/content' => Http::response('%PDF-1.4 direct content', 200),
    ]);

    $pdf = CompaniesHouse::documents()->pdf($metadataUrl);

    expect($pdf)->toBe('%PDF-1.4 direct content');
});

test('pdf download throws CompaniesHouseException when redirect has no Location header', function () use ($metadataUrl) {
    Http::fake([
        '*/document/abc123/content' => Http::response('', 302, []),
    ]);

    CompaniesHouse::documents()->pdf($metadataUrl);
})->throws(\DanJamesMills\CompaniesHouse\Exceptions\CompaniesHouseException::class);

test('pdf download throws when the redirected response fails', function () use ($metadataUrl) {
    Http::fake([
        '*/document/abc123/content' => Http::response('', 302, [
            'Location' => 'https://s3.example.com/filing.pdf',
        ]),
        'https://s3.example.com/filing.pdf' => Http::response('', 404),
    ]);

    CompaniesHouse::documents()->pdf($metadataUrl);
})->throws(\DanJamesMills\CompaniesHouse\Exceptions\NotFoundException::class);
