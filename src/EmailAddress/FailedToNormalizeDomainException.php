<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

class FailedToNormalizeDomainException extends \Exception
{

    public function __construct(string $value, ?\Throwable $exception = null)
    {
        parent::__construct("Unable to normalize domain: '$value'", 0, $exception);
    }

}
