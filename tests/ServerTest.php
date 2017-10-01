<?php

namespace duncan3dc\MailerTests;

use duncan3dc\Mailer\Server;

class ServerTest extends \PHPUnit_Framework_TestCase
{

    public function testSend()
    {
        $port = getenv("TRAVIS") ? 1025 : 25;
        $server = new Server("localhost", $port);

        $result = $server
            ->createMessage()
            ->setSubject("PHPUnit Test")
            ->addRecipient("test@example.com")
            ->addContent("Please ignore this message")
            ->send();

        $this->assertSame(1, $result);
    }
}
