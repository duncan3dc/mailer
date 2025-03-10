<?php

namespace duncan3dc\MailerTests;

use duncan3dc\Laravel\Blade;
use duncan3dc\Mailer\Email;
use duncan3dc\Mailer\Exception;
use duncan3dc\Mailer\Server;
use duncan3dc\ObjectIntruder\Intruder;
use Mockery;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    private $email;
    private $server;

    public function setUp(): void
    {
        $this->server = Mockery::mock(Server::class);
        $email = new Email($this->server);
        $this->email = new Intruder($email);

        Blade::addPath(__DIR__ . "/views");
    }


    public function tearDown(): void
    {
        Mockery::close();
    }


    public function testWithSubject(): void
    {
        $email = $this->email->withSubject("Test Subject");
        $email = new Intruder($email);

        $this->assertSame("Test Subject", $email->subject);
        $this->assertSame("", $this->email->subject);
    }


    public function testWithFromAddress1(): void
    {
        $email = $this->email->withFromAddress("test@example.com");
        $email = new Intruder($email);

        $this->assertSame("test@example.com", $email->from->getAddress());
        $this->assertSame(null, $this->email->from);
    }
    public function testWithFromAddress2(): void
    {
        $email = $this->email->withFromAddress("test@example.com", "Bob");
        $email = new Intruder($email);

        $this->assertSame("Bob", $email->from->getName());
        $this->assertSame("test@example.com", $email->from->getAddress());

        $this->assertSame(null, $this->email->from);
    }


    public function testWithReplyTo1(): void
    {
        $email = $this->email->withReplyTo("test@example.com");
        $email = new Intruder($email);

        $this->assertSame("test@example.com", $email->replyTo->getAddress());
        $this->assertSame("", $email->replyTo->getName());

        $this->assertSame(null, $this->email->replyTo);
    }
    public function testWithReplyTo2(): void
    {
        $email = $this->email->withReplyTo("test@example.com", "Bob");
        $email = new Intruder($email);

        $this->assertSame("test@example.com", $email->replyTo->getAddress());
        $this->assertSame("Bob", $email->replyTo->getName());

        $this->assertSame(null, $this->email->replyTo);
    }


    public function testWithoutReplyTo(): void
    {
        $email1 = $this->email->withReplyTo("test@example.com");
        $email1 = new Intruder($email1);

        $email2 = $email1->withoutReplyTo();
        $email2 = new Intruder($email2);

        $this->assertSame("test@example.com", $email1->replyTo->getAddress());

        $this->assertSame(null, $email2->replyTo);
    }


    public function testWithRecipient1(): void
    {
        $email = $this->email->withRecipient("test@example.com", "Example User");
        $email = new Intruder($email);

        $this->assertCount(1, $email->to);
        $this->assertSame("test@example.com", $email->to[0]->getAddress());
        $this->assertSame("Example User", $email->to[0]->getName());

        $this->assertSame([], $this->email->to);
    }
    public function testWithRecipient2(): void
    {
        $email = $this->email
            ->withRecipient("test1@example.com", "Example User1")
            ->withRecipient("test2@example.com", "Example User2");
        $email = new Intruder($email);

        $this->assertCount(2, $email->to);
        $this->assertSame("test1@example.com", $email->to[0]->getAddress());
        $this->assertSame("Example User1", $email->to[0]->getName());
        $this->assertSame("test2@example.com", $email->to[1]->getAddress());
        $this->assertSame("Example User2", $email->to[1]->getName());

        $this->assertSame([], $this->email->to);
    }
    public function testWithRecipient3(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Email "" does not comply with addr-spec of RFC 2822');
        $this->email->withRecipient("");
    }


    public function testWithoutRecipients(): void
    {
        $email1 = $this->email->withRecipient("test@example.com", "Example User");
        $email1 = new Intruder($email1);

        $email2 = $email1->withoutRecipients();
        $email2 = new Intruder($email2);

        $this->assertCount(1, $email1->to);
        $this->assertSame("test@example.com", $email1->to[0]->getAddress());
        $this->assertSame("Example User", $email1->to[0]->getName());

        $this->assertSame([], $email2->to);
    }


    public function testWithCc1(): void
    {
        $email = $this->email->withCc("test@example.com", "Example User");
        $email = new Intruder($email);

        $this->assertCount(1, $email->cc);
        $this->assertSame("test@example.com", $email->cc[0]->getAddress());
        $this->assertSame("Example User", $email->cc[0]->getName());

        $this->assertSame([], $this->email->cc);
    }
    public function testWithCc2(): void
    {
        $email = $this->email
            ->withCc("test1@example.com", "Example User1")
            ->withCc("test2@example.com", "Example User2");
        $email = new Intruder($email);

        $this->assertCount(2, $email->cc);
        $this->assertSame("test1@example.com", $email->cc[0]->getAddress());
        $this->assertSame("Example User1", $email->cc[0]->getName());
        $this->assertSame("test2@example.com", $email->cc[1]->getAddress());
        $this->assertSame("Example User2", $email->cc[1]->getName());

        $this->assertSame([], $this->email->cc);
    }


    public function testWithoutCc(): void
    {
        $email1 = $this->email->withCc("test@example.com", "Example User");
        $email1 = new Intruder($email1);

        $email2 = $email1->withoutCc();
        $email2 = new Intruder($email2);

        $this->assertCount(1, $email1->cc);
        $this->assertSame("test@example.com", $email1->cc[0]->getAddress());
        $this->assertSame("Example User", $email1->cc[0]->getName());

        $this->assertSame([], $email2->cc);
    }


    public function testWithBcc1(): void
    {
        $email = $this->email->withBcc("test@example.com", "Example User");
        $email = new Intruder($email);

        $this->assertCount(1, $email->bcc);
        $this->assertSame("test@example.com", $email->bcc[0]->getAddress());
        $this->assertSame("Example User", $email->bcc[0]->getName());

        $this->assertSame([], $this->email->bcc);
    }
    public function testWithBcc2(): void
    {
        $email = $this->email
            ->withBcc("test1@example.com", "Example User1")
            ->withBcc("test2@example.com", "Example User2");
        $email = new Intruder($email);

        $this->assertCount(2, $email->bcc);
        $this->assertSame("test1@example.com", $email->bcc[0]->getAddress());
        $this->assertSame("Example User1", $email->bcc[0]->getName());
        $this->assertSame("test2@example.com", $email->bcc[1]->getAddress());
        $this->assertSame("Example User2", $email->bcc[1]->getName());

        $this->assertSame([], $this->email->bcc);
    }


    public function testWithoutBcc(): void
    {
        $email1 = $this->email->withBcc("test@example.com", "Example User");
        $email1 = new Intruder($email1);

        $email2 = $email1->withoutBcc();
        $email2 = new Intruder($email2);

        $this->assertCount(1, $email1->bcc);
        $this->assertSame("test@example.com", $email1->bcc[0]->getAddress());
        $this->assertSame("Example User", $email1->bcc[0]->getName());

        $this->assertSame([], $email2->bcc);
    }


    public function testWithContent1(): void
    {
        $email = $this->email->withContent("Test Content");
        $email = new Intruder($email);

        $this->assertSame("Test Content", $email->content);
        $this->assertSame("", $this->email->content);
    }
    public function testWithContent2(): void
    {
        $email = $this->email
            ->withContent("Test Content1\n")
            ->withContent("Test Content2\n");
        $email = new Intruder($email);

        $this->assertSame("Test Content1\nTest Content2\n", $email->content);
        $this->assertSame("", $this->email->content);
    }


    public function testWithView1(): void
    {
        $email = $this->email->withView("test2", ["title" => "Test Title"]);
        $email = new Intruder($email);

        $this->assertSame(file_get_contents(__DIR__ . "/views/test2.html"), $email->content);
        $this->assertSame("", $this->email->content);
    }
    public function testWithView2(): void
    {
        $email = $this->email
            ->withView("test1")
            ->withView("test1");
        $email = new Intruder($email);

        $content = file_get_contents(__DIR__ . "/views/test1.blade.php");
        $this->assertSame($content . $content, $email->content);
        $this->assertSame("", $this->email->content);
    }
    public function testWithView3(): void
    {
        $this->expectException(\ErrorException::class);
        $this->email->withView("test3");
    }


    public function testWithoutContent(): void
    {
        $email1 = $this->email->withContent("HELLO!");
        $email1 = new Intruder($email1);

        $email2 = $email1->withoutContent();
        $email2 = new Intruder($email2);

        $this->assertSame("HELLO!", $email1->content);
        $this->assertSame("", $email2->content);
    }


    public function testWithAttachment(): void
    {
        $email = $this->email->withAttachment("/tmp/asdkjh.txt", "data.txt");
        $email = new Intruder($email);

        $this->assertSame(["/tmp/asdkjh.txt" => "data.txt"], $email->attachments);
        $this->assertSame([], $this->email->attachments);
    }


    public function testWithoutAttachments(): void
    {
        $email1 = $this->email->withAttachment("/tmp/test.txt");
        $email1 = new Intruder($email1);

        $email2 = $email1->withoutAttachments();
        $email2 = new Intruder($email2);

        $this->assertSame(["/tmp/test.txt" => null], $email1->attachments);
        $this->assertSame([], $email2->attachments);
    }


    public function testNoRecipients(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No recipients specified to send the email to");

        $this->email->send();
    }
}
