# mailer

A simple mailer abstraction for PHP with a clean interface

Full documentation is available at http://duncan3dc.github.io/mailer/  
PHPDoc API documentation is also available at [http://duncan3dc.github.io/mailer/api/](http://duncan3dc.github.io/mailer/api/namespaces/duncan3dc.Mailer.html)  

[![release](https://poser.pugx.org/duncan3dc/mailer/version.svg)](https://packagist.org/packages/duncan3dc/mailer)
[![build](https://travis-ci.org/duncan3dc/mailer.svg?branch=master)](https://travis-ci.org/duncan3dc/mailer)
[![coverage](https://codecov.io/gh/duncan3dc/mailer/graph/badge.svg)](https://codecov.io/gh/duncan3dc/mailer)


## Quick Example

Send an email using the local mail server:
```php
(new \duncan3dc\Mailer\Server)
    ->createMessage()
    ->withFromAddress("admin@example.com", "Administrator")
    ->withRecipient("user@example.com", "Your Name")
    ->withSubject("Test Email")
    ->withContent("Hello")
    ->withAttachment("/tmp/UGqucq", "sensible-filename.xls")
    ->send();
```

_Read more at http://duncan3dc.github.io/mailer/_  


## Changelog

A [Changelog](CHANGELOG.md) has been available since version 2.0.0


## Where to get help

Found a bug? Got a question? Just not sure how something works?  
Please [create an issue](//github.com/duncan3dc/mailer/issues) and I'll do my best to help out.  
Alternatively you can catch me on [Twitter](https://twitter.com/duncan3dc)


## duncan3dc/mailer for enterprise

Available as part of the Tidelift Subscription

The maintainers of duncan3dc/mailer and thousands of other packages are working with Tidelift to deliver commercial support and maintenance for the open source dependencies you use to build your applications. Save time, reduce risk, and improve code health, while paying the maintainers of the exact dependencies you use. [Learn more.](https://tidelift.com/subscription/pkg/packagist-duncan3dc-mailer?utm_source=packagist-duncan3dc-mailer&utm_medium=referral&utm_campaign=readme)
