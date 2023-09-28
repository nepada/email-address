<?php
declare(strict_types = 1);

namespace NepadaTests\EmailAddress;

use Nepada\EmailAddress\EmailAddress;
use Nepada\EmailAddress\InvalidEmailAddressException;
use NepadaTests\TestCase;
use Tester\Assert;

abstract class EmailAddressTestCase extends TestCase
{

    abstract protected function createEmailAddressFromString(string $value): EmailAddress;

    abstract protected function createEmailAddressFromDomainAndLocalPart(string $domain, string $localPart): EmailAddress;

    /**
     * @return mixed[]
     */
    abstract protected function getValidEmailAddresses(): array;

    /**
     * @return mixed[]
     */
    abstract protected function getValidEmailAddressesParts(): array;

    /**
     * @return mixed[]
     */
    abstract protected function getEmailAddressesForEqualityCheck(): array;

    /**
     * @dataProvider getValidEmailAddresses
     */
    public function testValidEmailAddressFromString(
        string $rawValue,
        string $expectedLocalPart,
        string $expectedDomain,
        string $expectedValue,
    ): void
    {
        $emailAddress = $this->createEmailAddressFromString($rawValue);

        Assert::same($rawValue, (string) $emailAddress);
        Assert::same($rawValue, $emailAddress->toString());

        Assert::same($expectedDomain, $emailAddress->getDomain());
        Assert::same($expectedLocalPart, $emailAddress->getLocalPart());
        Assert::same($expectedValue, $emailAddress->getValue());
    }

    /**
     * @dataProvider getValidEmailAddressesParts
     */
    public function testValidEmailAddressFromDomainAndLocalPart(
        string $localPart,
        string $domain,
        string $expectedLocalPart,
        string $expectedDomain,
        string $expectedValue,
    ): void
    {
        $emailAddress = $this->createEmailAddressFromDomainAndLocalPart($domain, $localPart);
        $rawValue = $localPart . '@' . $domain;

        Assert::same($rawValue, (string) $emailAddress);
        Assert::same($rawValue, $emailAddress->toString());

        Assert::same($expectedDomain, $emailAddress->getDomain());
        Assert::same($expectedLocalPart, $emailAddress->getLocalPart());
        Assert::same($expectedValue, $emailAddress->getValue());
    }

    /**
     * @dataProvider getInvalidEmailAddresses
     */
    public function testInvalidEmailAddressFromString(string $rawValue): void
    {
        Assert::exception(
            function () use ($rawValue): void {
                $this->createEmailAddressFromString($rawValue);
            },
            InvalidEmailAddressException::class,
        );
    }

    /**
     * @return mixed[]
     */
    protected function getInvalidEmailAddresses(): array
    {
        return array_merge(
            [
                ['rawValue' => ''],
            ],
            $this->domainAndLocalPartToValue($this->getInvalidEmailAddressParts()),
        );
    }

    /**
     * @dataProvider getInvalidEmailAddressParts
     */
    public function testInvalidEmailAddressFromDomainAndLocalPart(string $domain, string $localPart): void
    {
        Assert::exception(
            function () use ($domain, $localPart): void {
                $this->createEmailAddressFromDomainAndLocalPart($domain, $localPart);
            },
            InvalidEmailAddressException::class,
        );
    }

    /**
     * @return array<int, array<string, string>>
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

    /**
     * @dataProvider getEmailAddressesForEqualityCheck
     */
    public function testEquals(string $rawValue, EmailAddress $other, bool $isEqual): void
    {
        $emailAddress = $this->createEmailAddressFromString($rawValue);
        Assert::same($isEqual, $emailAddress->equals($other));
        Assert::same($isEqual, $other->equals($emailAddress));
    }

    /**
     * @param array<int, array<string, string>> $data
     * @return array<int, array<string, string>>
     */
    protected function domainAndLocalPartToValue(array $data): array
    {
        return array_map(
            function (array $arguments): array {
                $arguments['rawValue'] = $arguments['localPart'] . '@' . $arguments['domain'];
                return $arguments;
            },
            $data,
        );
    }

}
