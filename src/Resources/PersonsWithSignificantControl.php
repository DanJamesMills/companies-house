<?php

namespace DanJamesMills\CompaniesHouse\Resources;

use DanJamesMills\CompaniesHouse\Http\Client;

class PersonsWithSignificantControl
{
    public function __construct(
        protected readonly Client $client,
        protected readonly string $companyNumber,
    ) {}

    /**
     * List all PSC entries for a company.
     * GET /company/{company_number}/persons-with-significant-control
     *
     * @param  int|null  $itemsPerPage  Number of results per page
     * @param  int|null  $startIndex  The index of the first result
     * @param  bool|null  $registerView  Show register specific information
     */
    public function list(?int $itemsPerPage = null, ?int $startIndex = null, ?bool $registerView = null): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control", [
            'items_per_page' => $itemsPerPage,
            'start_index' => $startIndex,
            'register_view' => $registerView,
        ]);
    }

    /**
     * Get an individual PSC notification.
     * GET /company/{company_number}/persons-with-significant-control/individual/{notification_id}
     */
    public function individual(string $notificationId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control/individual/{$notificationId}");
    }

    /**
     * Get an individual beneficial owner PSC notification.
     * GET /company/{company_number}/persons-with-significant-control/individual-beneficial-owner/{notification_id}
     */
    public function individualBeneficialOwner(string $notificationId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control/individual-beneficial-owner/{$notificationId}");
    }

    /**
     * Get a corporate entity PSC notification.
     * GET /company/{company_number}/persons-with-significant-control/corporate-entity/{notification_id}
     */
    public function corporateEntity(string $notificationId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control/corporate-entity/{$notificationId}");
    }

    /**
     * Get a corporate entity beneficial owner PSC notification.
     * GET /company/{company_number}/persons-with-significant-control/corporate-entity-beneficial-owner/{notification_id}
     */
    public function corporateEntityBeneficialOwner(string $notificationId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control/corporate-entity-beneficial-owner/{$notificationId}");
    }

    /**
     * Get a legal person PSC notification.
     * GET /company/{company_number}/persons-with-significant-control/legal-person/{notification_id}
     */
    public function legalPerson(string $notificationId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control/legal-person/{$notificationId}");
    }

    /**
     * Get a legal person beneficial owner PSC notification.
     * GET /company/{company_number}/persons-with-significant-control/legal-person-beneficial-owner/{notification_id}
     */
    public function legalPersonBeneficialOwner(string $notificationId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control/legal-person-beneficial-owner/{$notificationId}");
    }

    /**
     * Get a super secure PSC.
     * GET /company/{company_number}/persons-with-significant-control/super-secure/{super_secure_id}
     */
    public function superSecure(string $superSecureId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control/super-secure/{$superSecureId}");
    }

    /**
     * Get a super secure beneficial owner PSC.
     * GET /company/{company_number}/persons-with-significant-control/super-secure-beneficial-owner/{super_secure_id}
     */
    public function superSecureBeneficialOwner(string $superSecureId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control/super-secure-beneficial-owner/{$superSecureId}");
    }

    /**
     * List PSC statements for a company.
     * GET /company/{company_number}/persons-with-significant-control-statements
     *
     * @param  int|null  $itemsPerPage  Number of results per page
     * @param  int|null  $startIndex  The index of the first result
     * @param  bool|null  $registerView  Show register specific information
     */
    public function listStatements(?int $itemsPerPage = null, ?int $startIndex = null, ?bool $registerView = null): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control-statements", [
            'items_per_page' => $itemsPerPage,
            'start_index' => $startIndex,
            'register_view' => $registerView,
        ]);
    }

    /**
     * Get a specific PSC statement.
     * GET /company/{company_number}/persons-with-significant-control-statements/{statement_id}
     */
    public function getStatement(string $statementId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control-statements/{$statementId}");
    }

    /**
     * List PSC notifications.
     * GET /company/{company_number}/persons-with-significant-control/{psc_id}/notifications
     */
    public function notifications(string $pscId): array
    {
        return $this->client->get("/company/{$this->companyNumber}/persons-with-significant-control/{$pscId}/notifications");
    }
}
