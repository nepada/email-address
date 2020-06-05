<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

/**
 * RFC compliant email address representation:
 * - domain part is normalized to lowercase ASCII representation
 * - local part is treated as **case sensitive**
 */
final class RfcEmailAddress extends EmailAddress
{

    public function toCaseInsensitiveEmailAddress(): CaseInsensitiveEmailAddress
    {
        return CaseInsensitiveEmailAddress::fromString($this->toString());
    }

}
