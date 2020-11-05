Email address value object
==========================

[![Build Status](https://github.com/nepada/email-address/workflows/CI/badge.svg)](https://github.com/nepada/email-address/actions?query=workflow%3ACI+branch%3Amaster)
[![Coverage Status](https://coveralls.io/repos/github/nepada/email-address/badge.svg?branch=master)](https://coveralls.io/github/nepada/email-address?branch=master)
[![Downloads this Month](https://img.shields.io/packagist/dm/nepada/email-address.svg)](https://packagist.org/packages/nepada/email-address)
[![Latest stable](https://img.shields.io/packagist/v/nepada/email-address.svg)](https://packagist.org/packages/nepada/email-address)


Installation
------------

Via Composer:

```sh
$ composer require nepada/email-address
```


Usage
-----

This package provides two implementations of email address value object:
1) `RfcEmailAddress` - it adheres to RFCs and treats local part of email address as case sensitive. The domain part is normalized to lower case ASCII representation.
2) `CaseInsensitiveEmailAddress` - the only difference from `RfcEmailAddress` is that local part is considered case insensitive and normalized to lower case.

It is up to you to decide which implementation suites your needs. If you want to support both implementations, use `Nepada\EmailAddress\EmailAddress` as a typehint. You can also cast one representation to the other using `RfcEmailAddress::toCaseInsensitiveEmailAddress()` and `CaseInsensitiveEmailAddress::toRfcEmailAddress()`.  

#### Creating value object
```php
$rfcEmailAddress = Nepada\EmailAddress\RfcEmailAddress::fromString('Real.example+suffix@HÁČKYčárky.cz');
$rfcEmailAddress = Nepada\EmailAddress\RfcEmailAddress::fromDomainAndLocalPart('HÁČKYčárky.cz', 'Real.example+suffix');

$ciEmailAddress = Nepada\EmailAddress\CaseInsensitiveEmailAddress::fromString('Real.example+suffix@HÁČKYčárky.cz');
$ciEmailAddress = Nepada\EmailAddress\CaseInsensitiveEmailAddress::fromDomainAndLocalPart('HÁČKYčárky.cz', 'Real.example+suffix');
```
`Nepada\EmailAddress\InvalidEmailAddressException` is thrown in case of invalid input value.

#### Converting back to string
Casting the value object to string, will result in the original (non-canonical) string representation of email address:
```php
echo((string) $emailAddress); // Real.example+suffix@HÁČKYčárky.cz
echo($emailAddress->toString()); // Real.example+suffix@HÁČKYčárky.cz
```

#### Canonical string representation of email address
```php
echo($emailAddress->getValue()); // real.example+suffix@xn--hkyrky-ptac70bc.cz
```

#### Getting normalized local and domain part separately
```php
echo($emailAddress->getLocalPart()); // real.example+suffix
echo($emailAddress->getDomain()); // xn--hkyrky-ptac70bc.cz
```


Integrations
------------

- [nepada/email-address-doctrine](https://github.com/nepada/email-address-doctrine) - Email address type for Doctrine.
- [nepada/email-address-input](https://github.com/nepada/email-address-input) - Email address form input for Nette forms.
