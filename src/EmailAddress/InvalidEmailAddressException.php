<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

class InvalidEmailAddressException extends \InvalidArgumentException
{

    public function __construct(string $value, ?\Throwable $exception = null)
    {
        parent::__construct("Invalid email address: '$value'", 0, $exception);
    }

}
