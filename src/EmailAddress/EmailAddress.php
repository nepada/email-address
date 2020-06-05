<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

use Nette;
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
     * @deprecated use CaseInsensitiveEmailAddress::getValue() instead
     * @return string
     */
    public function getLowercaseValue(): string
    {
        trigger_error('getLowercaseValue() is deprecated, use CaseInsensitiveEmailAddress::getValue() instead.', E_USER_DEPRECATED);
        return CaseInsensitiveEmailAddress::fromString($this->toString())->getValue();
    }

    /**
     * @deprecated use toString() instead
     * @return string
     */
    public function getOriginalValue(): string
    {
        trigger_error('getOriginalValue() is deprecated, use toString() instead.', E_USER_DEPRECATED);
        return $this->toString();
    }

    /**
     * Should return the original string representation of email address
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->rawValue;
    }

    /**
     * Alias for `toString()`
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

}
