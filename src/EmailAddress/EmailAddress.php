<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

interface EmailAddress
{

    /**
     * @param string $emailAddress
     * @return static
     * @throws InvalidEmailAddressException
     */
    public static function fromString(string $emailAddress): self;

    public function equals(self $other): bool;

    /**
     * Normalized local part of email address
     *
     * @return string
     */
    public function getLocalPart(): string;

    /**
     * Normalized domain part of email address
     *
     * @return string
     */
    public function getDomain(): string;

    /**
     * Canonical string representation of email address
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Should return the original string representation of email address
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Alias for `toString()`
     *
     * @return string
     */
    public function __toString(): string;

}
