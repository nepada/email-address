<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

use Nette\SmartObject;
use Nette\Utils\Validators;

abstract class AbstractEmailAddress implements EmailAddress
{

    use SmartObject;

    private string $rawValue;

    private string $localPart;

    private string $domain;

    final private function __construct(string $rawValue, string $domain, string $localPart)
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

        return new static($emailAddress, $normalizedDomain, $normalizedLocalPart);
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

    public function getLocalPart(): string
    {
        return $this->localPart;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getValue(): string
    {
        return $this->localPart . '@' . $this->domain;
    }

    public function toString(): string
    {
        return $this->rawValue;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function equals(EmailAddress $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

}
