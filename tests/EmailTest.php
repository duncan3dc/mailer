<?php

namespace duncan3dc\MailerTests;

use duncan3dc\Laravel\Blade;
use duncan3dc\Mailer\Email;
use duncan3dc\Mailer\Server;
use duncan3dc\ObjectIntruder\Intruder;
use Mockery;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    private $email;
    private $server;

    public function __construct()
    {
        $this->server = Mockery::mock(Server::class);
        $email = new Email($this->server);
        $this->email = new Intruder($email);

        Blade::addPath(__DIR__ . "/views");
    }


    public function testSetSubject()
    {
        $this->email->setSubject("Test Subject");
        $this->assertSame("Test Subject", $this->email->subject);
    }


    public function testSetFromAddress1()
    {
        $this->email->setFromAddress("test@example.com");
        $this->assertSame("test@example.com", $this->email->fromAddress);
    }
    public function testSetFromAddress2()
    {
        $this->email->setFromAddress("test@example.com", "Bob");
        $this->assertSame("Bob", $this->email->fromName);
        $this->assertSame("test@example.com", $this->email->fromAddress);
    }


    public function testSetReplyTo1()
    {
        $this->email->setReplyTo("test@example.com");
        $this->assertSame(["test@example.com" => "test@example.com"], $this->email->replyTo);
    }
    public function testSetReplyTo2()
    {
        $this->email->setReplyTo("test@example.com", "Bob");
        $this->assertSame(["test@example.com" => "Bob"], $this->email->replyTo);
    }


    public function testAddRecipient1()
    {
        $this->email->addRecipient("test@example.com", "Example User");
        $this->assertSame(["test@example.com" => "Example User"], $this->email->to);
    }
    public function testAddRecipient2()
    {
        $this->email->addRecipient("test1@example.com", "Example User1");
        $this->email->addRecipient("test2@example.com", "Example User2");

        $this->assertSame([
            "test1@example.com" =>  "Example User1",
            "test2@example.com" =>  "Example User2",
        ], $this->email->to);
    }


    public function testAddCc1()
    {
        $this->email->addCc("test@example.com", "Example User");
        $this->assertSame(["test@example.com" => "Example User"], $this->email->cc);
    }
    public function testAddCc2()
    {
        $this->email->addCc("test1@example.com", "Example User1");
        $this->email->addCc("test2@example.com", "Example User2");

        $this->assertSame([
            "test1@example.com" =>  "Example User1",
            "test2@example.com" =>  "Example User2",
        ], $this->email->cc);
    }


    public function testAddBcc1()
    {
        $this->email->addBcc("test@example.com", "Example User");
        $this->assertSame(["test@example.com" => "Example User"], $this->email->bcc);
    }
    public function testAddBcc2()
    {
        $this->email->addBcc("test1@example.com", "Example User1");
        $this->email->addBcc("test2@example.com", "Example User2");

        $this->assertSame([
            "test1@example.com" =>  "Example User1",
            "test2@example.com" =>  "Example User2",
        ], $this->email->bcc);
    }


    public function testAddContent1()
    {
        $this->email->addContent("Test Content");
        $this->assertSame("Test Content", $this->email->content);
    }
    public function testAddContent2()
    {
        $this->email->addContent("Test Content1\n");
        $this->email->addContent("Test Content2\n");
        $this->assertSame("Test Content1\nTest Content2\n", $this->email->content);
    }


    public function testAddView1()
    {
        $this->email->addView("test2", ["title" => "Test Title"]);
        $this->assertSame(file_get_contents(__DIR__ . "/views/test2.html"), $this->email->content);
    }
    public function testAddView2()
    {
        $this->email->addView("test1");
        $this->email->addView("test1");
        $content = file_get_contents(__DIR__ . "/views/test1.blade.php");
        $this->assertSame($content . $content, $this->email->content);
    }


    public function testAddAttachment()
    {
        $this->email->addAttachment("/tmp/asdkjh.txt", "data.txt");
        $this->assertSame(["/tmp/asdkjh.txt" => "data.txt"], $this->email->attachments);
    }
}
