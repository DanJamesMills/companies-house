<?php

namespace DanJamesMills\CompaniesHouse\Exceptions;

class AuthenticationException extends CompaniesHouseException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Companies House API key is invalid or unauthorised. Check your COMPANIES_HOUSE_API_KEY value.',
            statusCode: 401,
        );
    }
}
