Email address value object
==========================

[![Build Status](https://travis-ci.org/nepada/email-address.svg?branch=master)](https://travis-ci.org/nepada/email-address)
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

#### Creating value object
```php
$emailAddress = new Nepada\EmailAddress\EmailAddress('Real.example+suffix@HÁČKYčárky.cz');
```
`Nepada\EmailAddress\InvalidEmailAddressException` is thrown in case of invalid input value.

#### Converting back to string
```php
echo((string) $emailAddress); // Real.example+suffix@HÁČKYčárky.cz
echo($emailAddress->getOriginalValue()); // Real.example+suffix@HÁČKYčárky.cz
```

#### Email address with normalized domain part
```php
echo($emailAddress->getValue()); // Real.example+suffix@xn--hkyrky-ptac70bc.cz
```

#### Whole email address normalized and lowercased
```php
echo($emailAddress->getLowercaseValue()); // real.example+suffix@xn--hkyrky-ptac70bc.cz
```
Note: This is not RFC 5321 compliant, however in practice all major mail providers treat local part in case insensitive manner.

#### Getting local and domain part separately
```php
echo($emailAddress->getLocalPart()); // Real.example+suffix
echo($emailAddress->getDomain()); // xn--hkyrky-ptac70bc.cz
```


Integrations
------------

- [nepada/email-address-doctrine](https://github.com/nepada/email-address-doctrine) - Email address type for Doctrine.
- [nepada/email-address-input](https://github.com/nepada/email-address-input) - Email address form input for Nette forms.
