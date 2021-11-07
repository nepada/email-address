<?php
declare(strict_types = 1);

namespace NepadaTests\EmailAddress;

use Nepada\EmailAddress\CaseInsensitiveEmailAddress;
use Nepada\EmailAddress\RfcEmailAddress;
use Tester\Assert;

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
     * @return array<int, array<string, string>>
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

    /**
     * @return mixed[]
     */
    protected function getEmailAddressesForEqualityCheck(): array
    {
        return [
            [
                'rawValue' => 'Foo@HÁČKYčárky.cz',
                'other' => CaseInsensitiveEmailAddress::fromString('foo@xn--hkyrky-ptac70bc.cz'),
                'isEqual' => true,
            ],
            [
                'rawValue' => 'EXAMPLE@example.com',
                'other' => CaseInsensitiveEmailAddress::fromString('example@EXAMPLE.com'),
                'isEqual' => true,
            ],
            [
                'rawValue' => 'EXAMPLE@example.com',
                'other' => CaseInsensitiveEmailAddress::fromString('example+foo@EXAMPLE.com'),
                'isEqual' => false,
            ],
            [
                'rawValue' => 'EXAMPLE@EXAMPLE.com',
                'other' => RfcEmailAddress::fromString('example@EXAMPLE.com'),
                'isEqual' => true,
            ],
        ];
    }

    public function testToCaseInsensitiveEmailAddress(): void
    {
        $rawValue = 'Foo@Example.com';
        $emailAddress = $this->createEmailAddressFromString($rawValue)->toRfcEmailAddress();

        Assert::same($rawValue, $emailAddress->toString());
        Assert::same('Foo@example.com', $emailAddress->getValue());
    }

}


(new CaseInsensitiveEmailAddressTest())->run();
