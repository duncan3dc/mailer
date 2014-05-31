swiftmailer
===========

A simple wrapper around swiftmailer for php


Constructor Arguments
---------------------
The constructor accepts an array of options as it's only parameter. This parameter is option if you want to use the default settings.
* smtpServer - The SMTP server to use to send the email, if none is specified then localhost is used (port 25)
* username - If the SMTP server specified requires a username then specify it here
* password - If the SMTP server specified requires a password then specify it here
* fromAddress - The address to send the email from (default: no-reply@example.com)
* fromName - The name to send the email from (can be left blank to just use the fromAddress)


Public Methods
--------------
* setSubject(string $subject) - Set the subject of the message, clears any previously set subject
* setRecipient(mixed $address) - Set the recipient of the message, clears any previously set recipients. See [Email Address Arguments]
* addRecipient(mixed $address) - Similar to setRecipient() except it will not clear any previously set recipients
* setCc(mixed $address) - Similar to setRecipient() except it deals with the CC section of the message
* addCc(mixed $address) - Similar to setCc() except it will not clear any previously set CC addresses
* setBcc(mixed $address) - Similar to setCc() except it deals with the BCC section of the message
* addBcc(mixed $address) - Similar to setBcc() except it will not clear any previously set BCC addresses
* setReplyTo(mixed $address) - Set the reply-to header of the message to request that email clients reply to this address instead of the sender's address
* addReplyTo(mixed $address) - Similar to setReplyTo() except it will not clear any previously set reply-to addresses
* addContent(string $content) - Append content on to the message. All messages use html, and already have headers and a body tag included
* addAttachment(string $path [,string $filename]) - Attach a file to the message, you can override the filename using the $filename parameter, otherwise it is derived from the $path parameter
* send() - Send the message. This will throw an exception if no recipients have been set. Exceptions can also be thrown from within the swiftmailer project itself, otherwise this function will return true on sucess and false on a failure that didn't throw an exception


Email Address Arguments
-----------------------
All of the methods that deal with addresses accept $address as either a string or an array. When using a string then it is just the email address, when using an array the key should be the email address, and the value should be the name of the recipient


Examples
--------

The Mailer class uses a vendor name space of duncan3dc
```
use duncan3dc\Mailer;
```

-------------------

```
$mailer = new Mailer();
$mailer->setSubject("Test Email");
$mailer->setRecipient("name@example.com");
$mailer->addContent("Hello");
$mailer->send();
```

-------------------

```
$mailer = new Mailer([
    "fromAddress"   =>  "from@example.com",
    "fromName"      =>  "Mr Example",
]);
$mailer->setSubject("Test Email");
$mailer->setRecipient(["name@example.com" => "Your Name"]);
$mailer->send();
```

-------------------

```
$mailer = new Mailer();
$mailer->setSubject("Spreadsheet Attached");
$mailer->setRecipient("name@example.com");
$mailer->addAttachment("/tmp/UGqucq","sensible-filename.xls");
$mailer->send();
```
