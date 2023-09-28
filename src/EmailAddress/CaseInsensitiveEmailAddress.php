<?php
declare(strict_types = 1);

namespace Nepada\EmailAddress;

use Nette\Utils\Strings;

/**
 * Case insensitive email address representation:
 * - domain part is normalized to lowercase ASCII representation
 * - local part is normalized to lowercase - this is not compliant with RFC, but commonly used in practice
 */
final class CaseInsensitiveEmailAddress extends AbstractEmailAddress
{

    /**
     * @return string[]
     */
    protected static function normalizeDomainAndLocalPart(string $domain, string $localPart): array
    {
        [$normalizedDomain, $normalizedLocalPart] = parent::normalizeDomainAndLocalPart($domain, $localPart);
        $normalizedLocalPart = Strings::lower($normalizedLocalPart);

        return [$normalizedDomain, $normalizedLocalPart];
    }

    public function toRfcEmailAddress(): RfcEmailAddress
    {
        return RfcEmailAddress::fromString($this->toString());
    }

}
