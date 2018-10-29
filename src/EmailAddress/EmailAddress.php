<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

use Nette;
use Nette\Utils\Strings;
use Nette\Utils\Validators;

class EmailAddress
{

    use Nette\SmartObject;

    /** @var string */
    private $localPart;

    /** @var string */
    private $domain;

    /**
     * @param string $value
     * @throws InvalidEmailAddressException
     */
    public function __construct(string $value)
    {
        if (!Validators::isEmail($value)) {
            throw new InvalidEmailAddressException($value);
        }

        $parts = Strings::split($value, '~@~');
        $this->domain = array_pop($parts);
        $this->localPart = implode('@', $parts);
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
