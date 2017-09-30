# mailer

A simple mailer abstraction for PHP with a clean interface

Full documentation is available at http://duncan3dc.github.io/mailer/  
PHPDoc API documentation is also available at [http://duncan3dc.github.io/mailer/api/](http://duncan3dc.github.io/mailer/api/namespaces/duncan3dc.Mailer.html)  

[![Latest Stable Version](https://poser.pugx.org/duncan3dc/mailer/version.svg)](https://packagist.org/packages/duncan3dc/mailer)
[![Build Status](https://travis-ci.org/duncan3dc/mailer.svg?branch=master)](https://travis-ci.org/duncan3dc/mailer)
[![Coverage Status](https://coveralls.io/repos/github/duncan3dc/mailer/badge.svg)](https://coveralls.io/github/duncan3dc/mailer)


## Quick Examples

Send an email using the local mail server:
```php
$server = new \duncan3dc\Mailer\Server;

$email = $server->createMessage();
$email->setFromAddress("admin@example.com", "Administrator");
$email->setRecipient("user@example.com", "Your Name");
$email->setSubject("Test Email");
$email->addContent("Hello");
$email->addAttachment("/tmp/UGqucq", "sensible-filename.xls");
$email->send();
```

Most methods can be chained for a fluent interface:
```php
(new \duncan3dc\Mailer\Server)
    ->createMessage()
    ->setSubject("Test Email")
    ->setRecipient("name@example.com")
    ->addContent("Hello")
    ->send();
```


_Read more at http://duncan3dc.github.io/mailer/_  


## Changelog

A [Changelog](CHANGELOG.md) has been available since version 2.0.0


## Where to get help

Found a bug? Got a question? Just not sure how something works?  
Please [create an issue](//github.com/duncan3dc/mailer/issues) and I'll do my best to help out.  
Alternatively you can catch me on [Twitter](https://twitter.com/duncan3dc)
