<?php

namespace DanJamesMills\CompaniesHouse\Exceptions;

class StreamRangeException extends CompaniesHouseException
{
    public function __construct()
    {
        parent::__construct(
            message: 'The requested timepoint is no longer available in the stream queue. '
                   . 'Re-import a data snapshot and resume from its timepoint.',
            statusCode: 416,
        );
    }
}
