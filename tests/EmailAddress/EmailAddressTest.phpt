<?php
declare(strict_types = 1);

namespace NepadaTests\EmailAddress;

use Nepada\EmailAddress\EmailAddress;
use Nepada\EmailAddress\InvalidEmailAddressException;
use NepadaTests\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class EmailAddressTest extends TestCase
{

    /**
     * @dataProvider getValidEmailAddresses
     * @param string $value
     * @param string $expectedLocalPart
     * @param string $expectedDomain
     * @param string $expectedNormalizedValue
     * @param string $expectedLowercaseValue
     */
    public function testValidEmailAddressFromString(
        string $value,
        string $expectedLocalPart,
        string $expectedDomain,
        string $expectedNormalizedValue,
        string $expectedLowercaseValue
    ): void
    {
        $emailAddress = @EmailAddress::fromString($value);

        Assert::same($value, (string) $emailAddress);
        Assert::same($value, $emailAddress->toString());

        Assert::same($expectedDomain, $emailAddress->getDomain());
        Assert::same($expectedLocalPart, $emailAddress->getLocalPart());
        Assert::same($expectedNormalizedValue, $emailAddress->getValue());
    }

    /**
     * @dataProvider getValidEmailAddresses
     * @param string $localPart
     * @param string $domain
     * @param string $expectedLocalPart
     * @param string $expectedDomain
     * @param string $expectedNormalizedValue
     * @param string $expectedLowercaseValue
     */
    public function testValidEmailAddressFromDomainAndLocalPart(
        string $localPart,
        string $domain,
        string $expectedLocalPart,
        string $expectedDomain,
        string $expectedNormalizedValue,
        string $expectedLowercaseValue
    ): void
    {
        $emailAddress = @EmailAddress::fromDomainAndLocalPart($domain, $localPart);
        $value = $localPart . '@' . $domain;

        Assert::same($value, (string) $emailAddress);
        Assert::same($value, $emailAddress->toString());

        Assert::same($expectedDomain, $emailAddress->getDomain());
        Assert::same($expectedLocalPart, $emailAddress->getLocalPart());
        Assert::same($expectedNormalizedValue, $emailAddress->getValue());
    }

    /**
     * @dataProvider getInvalidEmailAddresses
     * @param string $value
     */
    public function testInvalidEmailAddressFromString(string $value): void
    {
        Assert::exception(
            function () use ($value): void {
                @EmailAddress::fromString($value);
            },
            InvalidEmailAddressException::class,
        );
    }

    /**
     * @dataProvider getInvalidEmailAddressParts
     * @param string $domain
     * @param string $localPart
     */
    public function testInvalidEmailAddressFromDomainAndLocalPart(string $domain, string $localPart): void
    {
        Assert::exception(
            function () use ($domain, $localPart): void {
                @EmailAddress::fromDomainAndLocalPart($domain, $localPart);
            },
            InvalidEmailAddressException::class,
        );
    }

    /**
     * @return mixed[]
     */
    protected function getValidEmailAddresses(): array
    {
        return [
            [
                'value' => 'Simple-example@Example.COM',
                'domain' => 'Example.COM',
                'localPart' => 'Simple-example',
                'expectedLocalPart' => 'Simple-example',
                'expectedDomain' => 'example.com',
                'expectedNormalizedValue' => 'Simple-example@example.com',
                'expectedLowercaseValue' => 'simple-example@example.com',
            ],
            [
                'value' => 'Real.example+suffix@HÁČKYčárky.cz',
                'domain' => 'HÁČKYčárky.cz',
                'localPart' => 'Real.example+suffix',
                'expectedLocalPart' => 'Real.example+suffix',
                'expectedDomain' => 'xn--hkyrky-ptac70bc.cz',
                'expectedNormalizedValue' => 'Real.example+suffix@xn--hkyrky-ptac70bc.cz',
                'expectedLowercaseValue' => 'real.example+suffix@xn--hkyrky-ptac70bc.cz',
            ],
            [
                'value' => '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"@strange.example.com',
                'domain' => 'strange.example.com',
                'localPart' => '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"',
                'expectedLocalPart' => '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"',
                'expectedDomain' => 'strange.example.com',
                'expectedNormalizedValue' => '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"@strange.example.com',
                'expectedLowercaseValue' => '"very.(),:;<>[]\".very.\"very@\\ \"very\".unusual"@strange.example.com',
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    protected function getInvalidEmailAddresses(): array
    {
        return [
            ['value' => ''],
            ['value' => 'foo'],
            ['value' => 'řehoř@example.com'],
            ['value' => 'john..doe@example.com'],
            ['value' => 'just"not"right@example.com'],
            ['value' => 'this is"not\allowed@example.com'],
            ['value' => 'this\ still\"not\\allowed@example.com'],
        ];
    }

    /**
     * @return mixed[]
     */
    protected function getInvalidEmailAddressParts(): array
    {
        return [
            [
                'localPart' => '',
                'domain' => 'example.com',
            ],
            [

                'localPart' => 'example',
                'domain' => '',
            ],
            [
                'localPart' => 'řehoř',
                'domain' => 'example.com',
            ],
            [
                'localPart' => 'john..doe',
                'domain' => 'example.com',
            ],
            [
                'localPart' => 'just"not"right',
                'domain' => 'example.com',
            ],
            [
                'localPart' => 'this is"not\allowed',
                'domain' => 'example.com',
            ],
            [
                'localPart' => 'this\ still\"not\\allowed',
                'domain' => 'example.com',
            ],
        ];
    }

}


(new EmailAddressTest())->run();
