<?php
declare(strict_types = 1);

namespace NepadaTests\EmailAddress;

use Nepada\EmailAddress\CaseInsensitiveEmailAddress;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class CaseInsensitiveEmailAddressTest extends EmailAddressTestCase
{

    protected function createEmailAddressFromString(string $value): CaseInsensitiveEmailAddress
    {
        return CaseInsensitiveEmailAddress::fromString($value);
    }

    protected function createEmailAddressFromDomainAndLocalPart(string $domain, string $localPart): CaseInsensitiveEmailAddress
    {
        return CaseInsensitiveEmailAddress::fromDomainAndLocalPart($domain, $localPart);
    }

    /**
     * @return mixed[]
     */
    protected function getValidEmailAddresses(): array
    {
        return $this->domainAndLocalPartToValue($this->getValidEmailAddressesParts());
    }

    /**
     * @return mixed[]
     */
    protected function getValidEmailAddressesParts(): array
    {
        return [
            [
                'domain' => 'Example.COM',
                'localPart' => 'Simple-example',
                'expectedLocalPart' => 'simple-example',
                'expectedDomain' => 'example.com',
                'expectedValue' => 'simple-example@example.com',
            ],
            [
                'domain' => 'HÁČKYčárky.cz',
                'localPart' => 'Real.example+suffix',
                'expectedLocalPart' => 'real.example+suffix',
                'expectedDomain' => 'xn--hkyrky-ptac70bc.cz',
                'expectedValue' => 'real.example+suffix@xn--hkyrky-ptac70bc.cz',
            ],
            [
                'domain' => 'strange.example.com',
                'localPart' => '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"',
                'expectedLocalPart' => '"very.(),:;<>[]\".very.\"very@\\ \"very\".unusual"',
                'expectedDomain' => 'strange.example.com',
                'expectedValue' => '"very.(),:;<>[]\".very.\"very@\\ \"very\".unusual"@strange.example.com',
            ],
        ];
    }

}


(new CaseInsensitiveEmailAddressTest())->run();
