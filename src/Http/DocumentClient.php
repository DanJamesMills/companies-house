<?php

namespace DanJamesMills\CompaniesHouse\Http;

use DanJamesMills\CompaniesHouse\Exceptions\AuthenticationException;
use DanJamesMills\CompaniesHouse\Exceptions\CompaniesHouseException;
use DanJamesMills\CompaniesHouse\Exceptions\NotFoundException;
use DanJamesMills\CompaniesHouse\Exceptions\RateLimitException;

class DocumentClient extends BaseClient
{
    /**
     * Fetch a document's metadata.
     * GET /document/{document_id}
     *
     * @throws AuthenticationException
     * @throws NotFoundException
     * @throws RateLimitException
     * @throws CompaniesHouseException
     */
    public function metadata(string $documentId): array
    {
        $uri = "/document/{$documentId}";
        $response = $this->http->get($uri);

        $this->throwIfFailed($response, $uri);

        return $response->json() ?? [];
    }

    /**
     * Download the raw content of a document (PDF or XHTML).
     * GET /document/{document_id}/content
     *
     * The API returns a 302 redirect to the actual file location; this method
     * follows it manually to preserve the Accept header for format selection.
     *
     * Available content types: application/pdf, application/xhtml+xml
     *
     * @throws AuthenticationException
     * @throws NotFoundException
     * @throws RateLimitException
     * @throws CompaniesHouseException
     */
    public function download(string $documentId, string $contentType = 'application/pdf'): string
    {
        $uri = "/document/{$documentId}/content";

        $response = $this->http
            ->accept($contentType)
            ->withoutRedirecting()
            ->get($uri);

        if ($response->status() === 302) {
            $location = $response->header('Location');

            if (empty($location)) {
                throw new CompaniesHouseException(
                    message: "Document download redirect received but no Location header was present [{$uri}].",
                    statusCode: 302,
                );
            }

            $fileResponse = $this->http->accept($contentType)->get($location);
            $this->throwIfFailed($fileResponse, $uri);

            return $fileResponse->body();
        }

        $this->throwIfFailed($response, $uri);

        return $response->body();
    }
}
