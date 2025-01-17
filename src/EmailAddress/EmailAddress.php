<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

interface EmailAddress extends \Stringable
{

    /**
     * @throws InvalidEmailAddressException
     */
    public static function fromString(string $emailAddress): static;

    public function equals(self $other): bool;

    /**
     * Normalized local part of email address
     */
    public function getLocalPart(): string;

    /**
     * Normalized domain part of email address
     */
    public function getDomain(): string;

    /**
     * Canonical string representation of email address
     */
    public function getValue(): string;

    /**
     * Should return the original string representation of email address
     */
    public function toString(): string;

    /**
     * Alias for `toString()`
     */
    public function __toString(): string;

}
