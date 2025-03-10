<?php

namespace duncan3dc\MailerTests;

use duncan3dc\Mailer\Server;
use duncan3dc\ObjectIntruder\Intruder;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    private $server;

    public function setUp(): void
    {
        $server = new Server;
        $this->server = new Intruder($server);
    }


    public function testWithCredentials(): void
    {
        $server = $this->server->withCredentials("bob", "secret");
        $server = new Intruder($server);

        $this->assertSame("bob", $server->username);
        $this->assertSame("secret", $server->password);
        $this->assertSame(null, $this->server->username);
        $this->assertSame(null, $this->server->password);
    }


    public function testWithEncryptionMethods1(): void
    {
        $server = $this->server->withEncryptionMethod("tls");
        $server = new Intruder($server);

        $this->assertSame("tls", $server->encryption);
        $this->assertSame(null, $this->server->encryption);
    }
    public function testWithEncryptionMethods2(): void
    {
        $server1 = new Server("email.com");
        $server1 = new Intruder($server1);

        $server2 = $server1->withEncryptionMethod("tls");
        $server2 = new Intruder($server2);

        $this->assertSame("tls", $server2->encryption);
        $this->assertSame("ssl", $server1->encryption);
    }


    public function testWithReturnPath(): void
    {
        $server = $this->server->withReturnPath("bounces@example.com");
        $server = new Intruder($server);

        $this->assertSame("bounces@example.com", $server->returnPath);
        $this->assertSame(null, $this->server->returnPath);
    }


    public function testSend(): void
    {
        $server = new Server("mailer-catcher", 1025);
        $server = $server->withEncryptionMethod("");

        $tmp = tempnam(sys_get_temp_dir(), "phpunit_");

        $server
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

        $this->assertTrue(true);
    }
}
