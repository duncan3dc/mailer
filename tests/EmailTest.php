<?php

namespace duncan3dc\MailerTests;

use duncan3dc\Laravel\Blade;
use duncan3dc\Mailer\Email;
use duncan3dc\Mailer\Exception;
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


    public function testWithSubject()
    {
        $email = $this->email->withSubject("Test Subject");
        $email = new Intruder($email);

        $this->assertSame("Test Subject", $email->subject);
        $this->assertSame("", $this->email->subject);
    }


    public function testWithFromAddress1()
    {
        $email = $this->email->withFromAddress("test@example.com");
        $email = new Intruder($email);

        $this->assertSame("test@example.com", $email->fromAddress);
        $this->assertSame("no-reply@example.com", $this->email->fromAddress);
    }
    public function testWithFromAddress2()
    {
        $email = $this->email->withFromAddress("test@example.com", "Bob");
        $email = new Intruder($email);

        $this->assertSame("Bob", $email->fromName);
        $this->assertSame("test@example.com", $email->fromAddress);

        $this->assertSame("", $this->email->fromName);
        $this->assertSame("no-reply@example.com", $this->email->fromAddress);
    }


    public function testWithReplyTo1()
    {
        $email = $this->email->withReplyTo("test@example.com");
        $email = new Intruder($email);

        $this->assertSame(["test@example.com" => "test@example.com"], $email->replyTo);
        $this->assertSame([], $this->email->replyTo);
    }
    public function testWithReplyTo2()
    {
        $email = $this->email->withReplyTo("test@example.com", "Bob");
        $email = new Intruder($email);

        $this->assertSame(["test@example.com" => "Bob"], $email->replyTo);
        $this->assertSame([], $this->email->replyTo);
    }


    public function testWithRecipient1()
    {
        $email = $this->email->withRecipient("test@example.com", "Example User");
        $email = new Intruder($email);

        $this->assertSame(["test@example.com" => "Example User"], $email->to);
        $this->assertSame([], $this->email->to);
    }
    public function testWithRecipient2()
    {
        $email = $this->email
            ->withRecipient("test1@example.com", "Example User1")
            ->withRecipient("test2@example.com", "Example User2");
        $email = new Intruder($email);

        $this->assertSame([
            "test1@example.com" =>  "Example User1",
            "test2@example.com" =>  "Example User2",
        ], $email->to);
        $this->assertSame([], $this->email->to);
    }
    public function testWithRecipient3()
    {
        $email = $this->email->withRecipient("");

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid recipient specified to send the email to");
        $email->send();
    }


    public function testWithCc1()
    {
        $email = $this->email->withCc("test@example.com", "Example User");
        $email = new Intruder($email);

        $this->assertSame(["test@example.com" => "Example User"], $email->cc);
        $this->assertSame([], $this->email->cc);
    }
    public function testWithCc2()
    {
        $email = $this->email
            ->withCc("test1@example.com", "Example User1")
            ->withCc("test2@example.com", "Example User2");
        $email = new Intruder($email);

        $this->assertSame([
            "test1@example.com" =>  "Example User1",
            "test2@example.com" =>  "Example User2",
        ], $email->cc);
        $this->assertSame([], $this->email->cc);
    }


    public function testWithBcc1()
    {
        $email = $this->email->withBcc("test@example.com", "Example User");
        $email = new Intruder($email);

        $this->assertSame(["test@example.com" => "Example User"], $email->bcc);
        $this->assertSame([], $this->email->bcc);
    }
    public function testWithBcc2()
    {
        $email = $this->email
            ->withBcc("test1@example.com", "Example User1")
            ->withBcc("test2@example.com", "Example User2");
        $email = new Intruder($email);

        $this->assertSame([
            "test1@example.com" =>  "Example User1",
            "test2@example.com" =>  "Example User2",
        ], $email->bcc);
        $this->assertSame([], $this->email->bcc);
    }


    public function testWithContent1()
    {
        $email = $this->email->withContent("Test Content");
        $email = new Intruder($email);

        $this->assertSame("Test Content", $email->content);
        $this->assertSame("", $this->email->content);
    }
    public function testWithContent2()
    {
        $email = $this->email
            ->withContent("Test Content1\n")
            ->withContent("Test Content2\n");
        $email = new Intruder($email);

        $this->assertSame("Test Content1\nTest Content2\n", $email->content);
        $this->assertSame("", $this->email->content);
    }


    public function testWithView1()
    {
        $email = $this->email->withView("test2", ["title" => "Test Title"]);
        $email = new Intruder($email);

        $this->assertSame(file_get_contents(__DIR__ . "/views/test2.html"), $email->content);
        $this->assertSame("", $this->email->content);
    }
    public function testWithView2()
    {
        $email = $this->email
            ->withView("test1")
            ->withView("test1");
        $email = new Intruder($email);

        $content = file_get_contents(__DIR__ . "/views/test1.blade.php");
        $this->assertSame($content . $content, $email->content);
        $this->assertSame("", $this->email->content);
    }


    public function testWithAttachment()
    {
        $email = $this->email->withAttachment("/tmp/asdkjh.txt", "data.txt");
        $email = new Intruder($email);

        $this->assertSame(["/tmp/asdkjh.txt" => "data.txt"], $email->attachments);
        $this->assertSame([], $this->email->attachments);
    }


    public function testNoRecipients()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No recipients specified to send the email to");

        $this->email->send();
    }
}
