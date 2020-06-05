<?php
declare(strict_types = 1);

namespace NepadaTests\EmailAddress;

use Nepada\EmailAddress\RfcEmailAddress;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class RfcEmailAddressTest extends EmailAddressTestCase
{

    protected function createEmailAddressFromString(string $value): RfcEmailAddress
    {
        return RfcEmailAddress::fromString($value);
    }

    protected function createEmailAddressFromDomainAndLocalPart(string $domain, string $localPart): RfcEmailAddress
    {
        return RfcEmailAddress::fromDomainAndLocalPart($domain, $localPart);
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
                'expectedLocalPart' => 'Simple-example',
                'expectedDomain' => 'example.com',
                'expectedValue' => 'Simple-example@example.com',
            ],
            [
                'domain' => 'HÁČKYčárky.cz',
                'localPart' => 'Real.example+suffix',
                'expectedLocalPart' => 'Real.example+suffix',
                'expectedDomain' => 'xn--hkyrky-ptac70bc.cz',
                'expectedValue' => 'Real.example+suffix@xn--hkyrky-ptac70bc.cz',
            ],
            [
                'domain' => 'strange.example.com',
                'localPart' => '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"',
                'expectedLocalPart' => '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"',
                'expectedDomain' => 'strange.example.com',
                'expectedValue' => '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"@strange.example.com',
            ],
        ];
    }

}


(new RfcEmailAddressTest())->run();
