<?php

namespace DanJamesMills\CompaniesHouse\Resources;

use DanJamesMills\CompaniesHouse\Http\DocumentClient;

class Documents
{
    public function __construct(protected readonly DocumentClient $client) {}

    /**
     * Fetch a document's metadata from a full document_metadata URL.
     *
     * Pass the URL directly from the filing history links.document_metadata field.
     */
    public function metadata(string $documentMetadataUrl): array
    {
        return $this->client->metadata($this->extractId($documentMetadataUrl));
    }

    /**
     * Download a document as a PDF from a full document_metadata URL.
     *
     * Returns the raw binary string — pipe to storage or stream as a response.
     *
     * Example:
     *   $pdf = CompaniesHouse::documents()->pdf($item['links']['document_metadata']);
     *   Storage::put('filings/accounts.pdf', $pdf);
     */
    public function pdf(string $documentMetadataUrl): string
    {
        return $this->client->download($this->extractId($documentMetadataUrl), 'application/pdf');
    }

    /**
     * Download a document as XHTML from a full document_metadata URL.
     *
     * Returns the raw XHTML string.
     */
    public function xhtml(string $documentMetadataUrl): string
    {
        return $this->client->download($this->extractId($documentMetadataUrl), 'application/xhtml+xml');
    }

    /**
     * Extract the document ID from a full document_metadata URL.
     *
     * e.g. https://document-api.company-information.service.gov.uk/document/abc123
     *      → 'abc123'
     */
    private function extractId(string $documentMetadataUrl): string
    {
        return basename(parse_url($documentMetadataUrl, PHP_URL_PATH));
    }
}
