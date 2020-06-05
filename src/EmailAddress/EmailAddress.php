<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

use Nette\SmartObject;

abstract class EmailAddress
{

    use SmartObject;

    /**
     * @deprecated
     * @param string $emailAddress
     * @return EmailAddress
     * @throws InvalidEmailAddressException
     */
    public static function fromString(string $emailAddress): self
    {
        trigger_error(
            'EmailAddress::fromString() is deprecated, use named constructor of a specific implementation (RfcEmailAddress|CaseInsensitiveEmailAddress)',
            E_USER_DEPRECATED,
        );
        return RfcEmailAddress::fromString($emailAddress);
    }

    /**
     * @deprecated
     * @param string $domain
     * @param string $localPart
     * @return EmailAddress
     * @throws InvalidEmailAddressException
     */
    public static function fromDomainAndLocalPart(string $domain, string $localPart): self
    {
        trigger_error(
            'EmailAddress::fromDomainAndLocalPart() is deprecated, use named constructor of a specific implementation (RfcEmailAddress|CaseInsensitiveEmailAddress)',
            E_USER_DEPRECATED,
        );
        return RfcEmailAddress::fromDomainAndLocalPart($domain, $localPart);
    }

    abstract public function equals(EmailAddress $other): bool;

    /**
     * Normalized local part of email address
     *
     * @return string
     */
    abstract public function getLocalPart(): string;

    /**
     * Normalized domain part of email address
     *
     * @return string
     */
    abstract public function getDomain(): string;

    /**
     * Canonical string representation of email address
     *
     * @return string
     */
    abstract public function getValue(): string;

    /**
     * Should return the original string representation of email address
     *
     * @return string
     */
    abstract public function toString(): string;

    /**
     * Alias for `toString()`
     *
     * @return string
     */
    abstract public function __toString(): string;

}
