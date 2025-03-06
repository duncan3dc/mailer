Changelog
=========

## x.y.z - UNRELEASED

--------

## 3.0.1 - 2025-03-06

### Fixed

* [Mailer] Any nullable arguments are not explicitly defined as such.
* [Upstream] Corrected the require for symfony/mailer.

--------

## 3.0.0 - 2024-02-04

### Changed

* [Upstream] Switched from swiftmailer to symfony/mailer
* [Mailer] The send() method no longer returns the number of successful recipients.
* [Support] Added support for PHP 8.2, 8.3 and 8.4

--------

## 2.1.0 - 2022-09-08

### Changed

* [Support] Added support for PHP 7.3, 8.0, and 8.1
* [Support] Dropped support for PHP 7.0, 7.1, and 7.2

--------

## 2.0.0 - 2017-11-25

### Added

* [Docs] Created a changelog!
* [Support] Drop support for PHP 5.6
* [Support] Add support for PHP 7.1
* [Support] Add support for PHP 7.2

### Changed

* [Docs] Rename to project to duncan3dc/mailer
* [Email] This class is now immutable, and implements the Emailinterface
* [Server] This class is now immutable, and implements the Emailinterface
* [Exceptions] The library now throws a duncan3dc\Mailer\Exception.

### Removed

* [Mailer] The all-in-one Mailer class has now been removed.
