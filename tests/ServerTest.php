<?php

namespace duncan3dc\MailerTests;

use duncan3dc\Mailer\Server;
use duncan3dc\ObjectIntruder\Intruder;

class ServerTest extends \PHPUnit_Framework_TestCase
{
    private $server;

    public function __construct()
    {
        $server = new Server;
        $this->server = new Intruder($server);
    }


    public function testWithCredentials()
    {
        $server = $this->server->withCredentials("bob", "secret");
        $server = new Intruder($server);

        $this->assertSame("bob", $server->username);
        $this->assertSame("secret", $server->password);
        $this->assertSame(null, $this->server->username);
        $this->assertSame(null, $this->server->password);
    }


    public function testWithEncryptionMethods()
    {
        $server = $this->server->withEncryptionMethod("tls");
        $server = new Intruder($server);

        $this->assertSame("tls", $server->encryption);
        $this->assertSame("ssl", $this->server->encryption);
    }


    public function testWithReturnPath()
    {
        $server = $this->server->withReturnPath("bounces@example.com");
        $server = new Intruder($server);

        $this->assertSame("bounces@example.com", $server->returnPath);
        $this->assertSame(null, $this->server->returnPath);
    }


    public function testSend()
    {
        $port = getenv("TRAVIS") ? 1025 : 25;
        $server = new Server("localhost", $port);

        $tmp = tempnam(sys_get_temp_dir(), "phpunit_");

        $result = $server
            ->withReturnPath("bounces@example.com")
            ->createMessage()
            ->withSubject("PHPUnit Test")
            ->withRecipient("test@example.com")
            ->withCc("cc@example.com")
            ->withBcc("bcc@example.com")
            ->withReplyTo("nobody@example.com")
            ->withContent("Please ignore this message")
            ->withAttachment($tmp, "test.txt")
            ->send();

        unlink($tmp);

        $this->assertSame(3, $result);
    }
}
