<?php

namespace duncan3dc\MailerTests;

use duncan3dc\Laravel\Blade;
use duncan3dc\Mailer\Mailer;
use duncan3dc\ObjectIntruder\Intruder;

class MailerTest extends \PHPUnit_Framework_TestCase
{
    private $mailer;

    public function setUp()
    {
        $mailer = new Mailer;
        $this->mailer = new Intruder($mailer);

        Blade::addPath(__DIR__ . "/views");
    }


    public function testSetSubject()
    {
        $this->mailer->setSubject("Test Subject");
        $this->assertSame("Test Subject", $this->mailer->subject);
    }


    public function testSetRecipient1()
    {
        $this->mailer->setRecipient("test@example.com");
        $this->assertSame(["test@example.com" => "test@example.com"], $this->mailer->to);
    }
    public function testSetRecipient2()
    {
        $this->mailer->setRecipient(["test@example.com" => "Example User"]);
        $this->assertSame(["test@example.com" => "Example User"], $this->mailer->to);
    }
    public function testSetRecipient3()
    {
        $this->mailer->setRecipient("test2@example.com");
        $this->mailer->setRecipient(["test@example.com" => "Example User"]);
        $this->assertSame(["test@example.com" => "Example User"], $this->mailer->to);
    }


    public function testAddRecipient1()
    {
        $this->mailer->addRecipient(["test@example.com" => "Example User"]);
        $this->assertSame(["test@example.com" => "Example User"], $this->mailer->to);
    }
    public function testAddRecipient2()
    {
        $this->mailer->addRecipient(["test1@example.com" => "Example User1"]);
        $this->mailer->addRecipient(["test2@example.com" => "Example User2"]);

        $this->assertSame([
            "test1@example.com" =>  "Example User1",
            "test2@example.com" =>  "Example User2",
        ], $this->mailer->to);
    }


    public function testSetCc1()
    {
        $this->mailer->setCc(["test@example.com" => "Example User"]);
        $this->assertSame(["test@example.com" => "Example User"], $this->mailer->cc);
    }
    public function testSetCc2()
    {
        $this->mailer->setCc("test2@example.com");
        $this->mailer->setCc(["test@example.com" => "Example User"]);
        $this->assertSame(["test@example.com" => "Example User"], $this->mailer->cc);
    }


    public function testAddCc1()
    {
        $this->mailer->addCc(["test@example.com" => "Example User"]);
        $this->assertSame(["test@example.com" => "Example User"], $this->mailer->cc);
    }
    public function testAddCc2()
    {
        $this->mailer->addCc(["test1@example.com" => "Example User1"]);
        $this->mailer->addCc(["test2@example.com" => "Example User2"]);

        $this->assertSame([
            "test1@example.com" =>  "Example User1",
            "test2@example.com" =>  "Example User2",
        ], $this->mailer->cc);
    }


    public function testSetBcc1()
    {
        $this->mailer->setBcc(["test@example.com" => "Example User"]);
        $this->assertSame(["test@example.com" => "Example User"], $this->mailer->bcc);
    }
    public function testSetBcc2()
    {
        $this->mailer->setBcc("test2@example.com");
        $this->mailer->setBcc(["test@example.com" => "Example User"]);
        $this->assertSame(["test@example.com" => "Example User"], $this->mailer->bcc);
    }


    public function testAddBcc1()
    {
        $this->mailer->addBcc(["test@example.com" => "Example User"]);
        $this->assertSame(["test@example.com" => "Example User"], $this->mailer->bcc);
    }
    public function testAddBcc2()
    {
        $this->mailer->addBcc(["test1@example.com" => "Example User1"]);
        $this->mailer->addBcc(["test2@example.com" => "Example User2"]);

        $this->assertSame([
            "test1@example.com" =>  "Example User1",
            "test2@example.com" =>  "Example User2",
        ], $this->mailer->bcc);
    }


    public function testSetContent1()
    {
        $this->mailer->addContent("Test Content");
        $this->mailer->setContent("Test Content");
        $this->assertSame("Test Content", $this->mailer->content);
    }


    public function testAddContent1()
    {
        $this->mailer->addContent("Test Content");
        $this->assertSame("Test Content", $this->mailer->content);
    }
    public function testAddContent2()
    {
        $this->mailer->addContent("Test Content1\n");
        $this->mailer->addContent("Test Content2\n");
        $this->assertSame("Test Content1\nTest Content2\n", $this->mailer->content);
    }


    public function testSetView()
    {
        $this->mailer->addView("test1");
        $this->mailer->setView("test1");
        $this->assertSame(file_get_contents(__DIR__ . "/views/test1.blade.php"), $this->mailer->content);
    }


    public function testAddView1()
    {
        $this->mailer->addView("test2", ["title" => "Test Title"]);
        $this->assertSame(file_get_contents(__DIR__ . "/views/test2.html"), $this->mailer->content);
    }
    public function testAddView2()
    {
        $this->mailer->addView("test1");
        $this->mailer->addView("test1");
        $content = file_get_contents(__DIR__ . "/views/test1.blade.php");
        $this->assertSame($content . $content, $this->mailer->content);
    }


    public function testSend()
    {
        $port = getenv("TRAVIS") ? 1025 : 25;
        $result = (new Mailer(["local-port" => $port]))
            ->setSubject("PHPUnit Test")
            ->addContent("Please ignore this message")
            ->send("test@example.com");
        $this->assertSame(1, $result);
    }
}
