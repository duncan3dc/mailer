swiftmailer
===========

A simple wrapper around swiftmailer for php

[![Build Status](https://travis-ci.org/duncan3dc/swiftmailer.svg?branch=master)](https://travis-ci.org/duncan3dc/swiftmailer)
[![Latest Stable Version](https://poser.pugx.org/duncan3dc/swiftmailer/version.svg)](https://packagist.org/packages/duncan3dc/swiftmailer)


Constructor Arguments
---------------------
The constructor accepts an array of options as the only parameter. This parameter is optional if you want to use the default settings.
* smtpServer: string - The SMTP server to use to send the email, if none is specified then localhost is used (port 25)
* username: string - If the SMTP server specified requires a username then specify it here
* password: string - If the SMTP server specified requires a password then specify it here
* fromAddress: string - The address to send the email from (default: no-reply@example.com)
* fromName: string - The name to send the email from (can be left blank to just use the fromAddress)
* encryption: string - The type of encryption used (only has an effect when smtpServer is specified), the default is "ssl"
* port: int - The port to connect on (only has an effect when smtpServer is specified), the default is 465
* returnPath: string - An email address to return bounced messages to


Public Methods
--------------
* setSubject(string $subject): Mailer - Set the subject of the message, clears any previously set subject
* setRecipient(string $address[, string $name]): Mailer - Set the recipient of the message, clears any previously set recipients
* addRecipient(string $address[, string $name]): Mailer - Similar to setRecipient() except it will not clear any previously set recipients
* setCc(string $address[, string $name]): Mailer - Similar to setRecipient() except it deals with the CC section of the message
* addCc(string $address[, string $name]): Mailer - Similar to setCc() except it will not clear any previously set CC addresses
* setBcc(string $address[, string $name]): Mailer - Similar to setCc() except it deals with the BCC section of the message
* addBcc(string $address[, string $name]): Mailer - Similar to setBcc() except it will not clear any previously set BCC addresses
* setReplyTo(string $address[, string $name]): Mailer - Set the reply-to header of the message to request that email clients reply to this address instead of the sender's address
* setContent(string $content): Mailer - Discard any previous content and use the content provided. All messages use html, and already have headers and a body tag included
* addContent(string $content): Mailer - Append content on to the message
* addAttachment(string $path [,string $filename]): Mailer - Attach a file to the message, you can override the filename using the $filename parameter, otherwise it is derived from the $path parameter
* send(): boolean - Send the message. This will throw an exception if no recipients have been set. Exceptions can also be thrown from within the swiftmailer project itself, otherwise this function will return true on sucess and false on a failure that didn't throw an exception


Examples
--------

The Mailer class uses a vendor name space of duncan3dc\SwiftMailer
```php
use duncan3dc\SwiftMailer\Mailer;
```

-------------------

```php
$mailer = new Mailer();
$mailer->setSubject("Test Email");
$mailer->setRecipient(["name@example.com" => "Your Name"]);
$mailer->addContent("Hello");
$mailer->send();
```

-------------------

```php
$mailer = new Mailer([
    "fromAddress"   =>  "from@example.com",
    "fromName"      =>  "Mr Example",
]);
$mailer->setSubject("Test Email");
$mailer->send("name@example.com");
```

-------------------

```php
$mailer = new Mailer();
$mailer->setSubject("Spreadsheet Attached");
$mailer->setRecipient("name@example.com");
$mailer->addAttachment("/tmp/UGqucq","sensible-filename.xls");
$mailer->send();
```

-------------------

If method chaining is your thing then all the public methods except send() return the instance of the Mailer class
```php
(new Mailer())
    ->setSubject("Test Email")
    ->setRecipient("name@example.com")
    ->addContent("Hello")
    ->send();
```
