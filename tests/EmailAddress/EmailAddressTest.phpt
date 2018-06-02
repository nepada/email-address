<?php
declare(strict_types = 1);

namespace NepadaTests\EmailAddress;

use Nepada\EmailAddress\EmailAddress;
use Nepada\EmailAddress\InvalidEmailAddressException;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class EmailAddressTest extends Tester\TestCase
{

    /**
     * @dataProvider getValidEmailAddresses
     * @param string $value
     * @param string $localPart
     * @param string $domain
     * @param string $normalizedValue
     * @param string $lowercaseValue
     */
    public function testValidEmailAddress(string $value, string $localPart, string $domain, string $normalizedValue, string $lowercaseValue): void
    {
        $emailAddress = new EmailAddress($value);

        Assert::same($value, (string) $emailAddress);
        Assert::same($value, $emailAddress->getOriginalValue());

        Assert::same($domain, $emailAddress->getDomain());
        Assert::same($localPart, $emailAddress->getLocalPart());
        Assert::same($normalizedValue, $emailAddress->getValue());
        Assert::same($lowercaseValue, $emailAddress->getLowercaseValue());
    }

    /**
     * @dataProvider getInvalidEmailAddresses
     * @param string $value
     */
    public function testInvalidEmailAddress(string $value): void
    {
        Assert::exception(
            function () use ($value): void {
                new EmailAddress($value);
            },
            InvalidEmailAddressException::class
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
                'localPart' => 'Simple-example',
                'domain' => 'example.com',
                'normalizedValue' => 'Simple-example@example.com',
                'lowercaseValue' => 'simple-example@example.com',
            ],
            [
                'value' => 'Real.example+suffix@HÁČKYčárky.cz',
                'localPart' => 'Real.example+suffix',
                'domain' => 'xn--hkyrky-ptac70bc.cz',
                'normalizedValue' => 'Real.example+suffix@xn--hkyrky-ptac70bc.cz',
                'lowercaseValue' => 'real.example+suffix@xn--hkyrky-ptac70bc.cz',
            ],
            [
                'value' => '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"@strange.example.com',
                'localPart' => '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"',
                'domain' => 'strange.example.com',
                'normalizedValue' => '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"@strange.example.com',
                'lowercaseValue' => '"very.(),:;<>[]\".very.\"very@\\ \"very\".unusual"@strange.example.com',
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

}


(new EmailAddressTest())->run();
