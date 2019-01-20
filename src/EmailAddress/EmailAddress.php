<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

use Nette;
use Nette\Utils\Strings;
use Nette\Utils\Validators;

final class EmailAddress
{

    use Nette\SmartObject;

    /** @var string */
    private $localPart;

    /** @var string */
    private $domain;

    private function __construct(string $domain, string $localPart)
    {
        $this->domain = $domain;
        $this->localPart = $localPart;
    }

    public static function fromString(string $emailAddress): self
    {
        if (!Validators::isEmail($emailAddress)) {
            throw new InvalidEmailAddressException($emailAddress);
        }

        $parts = Strings::split($emailAddress, '~@~');
        $domain = array_pop($parts);
        $localPart = implode('@', $parts);

        return new static($domain, $localPart);
    }

    public static function fromDomainAndLocalPart(string $domain, string $localPart): self
    {
        $emailAddress = $localPart . '@' . $domain;
        if (!Validators::isEmail($emailAddress)) {
            throw new InvalidEmailAddressException($emailAddress);
        }

        return new static($domain, $localPart);
    }

    /**
     * Local part of the email address as it was originally passed (with preserved case).
     *
     * @return string
     */
    public function getLocalPart(): string
    {
        return $this->localPart;
    }

    /**
     * Normalized (lowercase) domain part of the email address.
     *
     * @return string
     */
    public function getDomain(): string
    {
        $normalizedDomain = idn_to_ascii($this->domain, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);
        if ($normalizedDomain === false) {
            throw new FailedToNormalizeDomainException($this->domain);
        }

        return $normalizedDomain;
    }

    /**
     * Email address with normalized (lowercase) domain part.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->localPart . '@' . $this->getDomain();
    }

    /**
     * Email address completely converted to lowercase.
     * Note: This is not RFC 5321 compliant, however in practice all major mail providers treat local part in case insensitive manner.
     *
     * @return string
     */
    public function getLowercaseValue(): string
    {
        return Strings::lower($this->localPart) . '@' . $this->getDomain();
    }

    /**
     * Email address as it was originally passed (with preserved case).
     *
     * @return string
     */
    public function getOriginalValue(): string
    {
        return $this->localPart . '@' . $this->domain;
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
