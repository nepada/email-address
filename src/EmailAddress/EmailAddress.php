<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

use Nette;
use Nette\Utils\Strings;
use Nette\Utils\Validators;

abstract class EmailAddress
{

    use Nette\SmartObject;

    private string $rawValue;

    private string $localPart;

    private string $domain;

    private function __construct(string $rawValue, string $domain, string $localPart)
    {
        $this->rawValue = $rawValue;
        $this->domain = $domain;
        $this->localPart = $localPart;
    }

    /**
     * @param string $emailAddress
     * @return static
     * @throws InvalidEmailAddressException
     */
    public static function fromString(string $emailAddress): self
    {
        if (! Validators::isEmail($emailAddress)) {
            throw new InvalidEmailAddressException($emailAddress);
        }

        $parts = explode('@', $emailAddress);
        $domain = (string) array_pop($parts);
        $localPart = implode('@', $parts);
        [$normalizedDomain, $normalizedLocalPart] = static::normalizeDomainAndLocalPart($domain, $localPart);

        $emailAddressClass = static::class;
        if ($emailAddressClass === self::class) { // BC
            $emailAddressClass = RfcEmailAddress::class;
        }

        return new $emailAddressClass($emailAddress, $normalizedDomain, $normalizedLocalPart);
    }

    /**
     * @param string $domain
     * @param string $localPart
     * @return static
     * @throws InvalidEmailAddressException
     */
    public static function fromDomainAndLocalPart(string $domain, string $localPart): self
    {
        return static::fromString($localPart . '@' . $domain);
    }

    /**
     * @param string $domain
     * @param string $localPart
     * @return string[]
     */
    protected static function normalizeDomainAndLocalPart(string $domain, string $localPart): array
    {
        $normalizedDomain = idn_to_ascii($domain, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);
        if ($normalizedDomain === false) {
            throw new FailedToNormalizeDomainException($domain);
        }

        return [$normalizedDomain, $localPart];
    }

    /**
     * Normalized local part of email address
     *
     * @return string
     */
    public function getLocalPart(): string
    {
        return $this->localPart;
    }

    /**
     * Normalized domain part of email address
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Canonical string representation of email address
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->localPart . '@' . $this->domain;
    }

    /**
     * Email address completely converted to lowercase.
     * Note: This is not RFC 5321 compliant, however in practice all major mail providers treat local part in case insensitive manner.
     *
     * @return string
     */
    public function getLowercaseValue(): string
    {
        return Strings::lower($this->localPart) . '@' . $this->domain;
    }

    /**
     * Original string representation of email address
     *
     * @return string
     */
    public function getOriginalValue(): string
    {
        return $this->rawValue;
    }

    public function toString(): string
    {
        return $this->getOriginalValue();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

}
