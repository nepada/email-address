<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

use Nette\SmartObject;
use Nette\Utils\Validators;

abstract class AbstractEmailAddress implements EmailAddress
{

    use SmartObject;

    private readonly string $rawValue;

    private readonly string $localPart;

    private readonly string $domain;

    final private function __construct(string $rawValue, string $domain, string $localPart)
    {
        $this->rawValue = $rawValue;
        $this->domain = $domain;
        $this->localPart = $localPart;
    }

    /**
     * @throws InvalidEmailAddressException
     */
    final public static function fromString(string $emailAddress): static
    {
        if (! Validators::isEmail($emailAddress)) {
            throw new InvalidEmailAddressException($emailAddress);
        }

        $parts = explode('@', $emailAddress);
        $domain = array_pop($parts);
        $localPart = implode('@', $parts);
        [$normalizedDomain, $normalizedLocalPart] = static::normalizeDomainAndLocalPart($domain, $localPart);

        return new static($emailAddress, $normalizedDomain, $normalizedLocalPart);
    }

    /**
     * @throws InvalidEmailAddressException
     */
    final public static function fromDomainAndLocalPart(string $domain, string $localPart): static
    {
        return static::fromString($localPart . '@' . $domain);
    }

    /**
     * @return list<string>
     */
    protected static function normalizeDomainAndLocalPart(string $domain, string $localPart): array
    {
        $normalizedDomain = idn_to_ascii($domain, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);
        if ($normalizedDomain === false) {
            throw new FailedToNormalizeDomainException($domain);
        }

        return [$normalizedDomain, $localPart];
    }

    final public function getLocalPart(): string
    {
        return $this->localPart;
    }

    final public function getDomain(): string
    {
        return $this->domain;
    }

    final public function getValue(): string
    {
        return $this->localPart . '@' . $this->domain;
    }

    final public function toString(): string
    {
        return $this->rawValue;
    }

    final public function __toString(): string
    {
        return $this->toString();
    }

    final public function equals(EmailAddress $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

}
