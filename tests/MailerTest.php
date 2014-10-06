<?php

namespace duncan3dc\SwiftMailer;

class MailerTest extends \PHPUnit_Framework_TestCase
{

    private function getProperty(Mailer $mailer, $name)
    {
        $reflection = new \ReflectionClass(__NAMESPACE__ . "\\Mailer");
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);
        return $property->getValue($mailer);
    }


    private function checkProperty(Mailer $mailer, $name, $check)
    {
        $result = $this->getProperty($mailer, $name);
        $this->assertSame($check, $result);
    }


    public function testSetSubject()
    {
        $subject = "Test Subject";
        $mailer = new Mailer;
        $mailer->setSubject($subject);
        $this->checkProperty($mailer, "subject", $subject);
    }


    public function testSetRecipient1()
    {
        $mailer = new Mailer;
        $mailer->setRecipient("test@example.com");
        $this->checkProperty($mailer, "to", ["test@example.com" => "test@example.com"]);
    }


    public function testSetRecipient2()
    {
        $address = ["test@example.com" => "Example User"];
        $mailer = new Mailer;
        $mailer->setRecipient($address);
        $this->checkProperty($mailer, "to", $address);
    }


    public function testSetRecipient3()
    {
        $address = ["test@example.com" => "Example User"];
        $mailer = new Mailer;
        $mailer->setRecipient("test2@example.com");
        $mailer->setRecipient($address);
        $this->checkProperty($mailer, "to", $address);
    }


    public function testAddRecipient1()
    {
        $address = ["test@example.com" => "Example User"];
        $mailer = new Mailer;
        $mailer->addRecipient($address);
        $this->checkProperty($mailer, "to", $address);
    }


    public function testAddRecipient2()
    {
        $address1 = ["test1@example.com" => "Example User1"];
        $address2 = ["test2@example.com" => "Example User2"];
        $mailer = new Mailer;
        $mailer->addRecipient($address1);
        $mailer->addRecipient($address2);
        $this->checkProperty($mailer, "to", array_merge($address1, $address2));
    }


    public function testSetCc1()
    {
        $address = ["test@example.com" => "Example User"];
        $mailer = new Mailer;
        $mailer->setCc($address);
        $this->checkProperty($mailer, "cc", $address);
    }


    public function testSetCc2()
    {
        $address = ["test@example.com" => "Example User"];
        $mailer = new Mailer;
        $mailer->setCc("test2@example.com");
        $mailer->setCc($address);
        $this->checkProperty($mailer, "cc", $address);
    }


    public function testAddCc1()
    {
        $address = ["test@example.com" => "Example User"];
        $mailer = new Mailer;
        $mailer->addCc($address);
        $this->checkProperty($mailer, "cc", $address);
    }


    public function testAddCc2()
    {
        $address1 = ["test1@example.com" => "Example User1"];
        $address2 = ["test2@example.com" => "Example User2"];
        $mailer = new Mailer;
        $mailer->addCc($address1);
        $mailer->addCc($address2);
        $this->checkProperty($mailer, "cc", array_merge($address1, $address2));
    }


    public function testSetBcc1()
    {
        $address = ["test@example.com" => "Example User"];
        $mailer = new Mailer;
        $mailer->setBcc($address);
        $this->checkProperty($mailer, "bcc", $address);
    }


    public function testSetBcc2()
    {
        $address = ["test@example.com" => "Example User"];
        $mailer = new Mailer;
        $mailer->setBcc("test2@example.com");
        $mailer->setBcc($address);
        $this->checkProperty($mailer, "bcc", $address);
    }


    public function testAddBcc1()
    {
        $address = ["test@example.com" => "Example User"];
        $mailer = new Mailer;
        $mailer->addBcc($address);
        $this->checkProperty($mailer, "bcc", $address);
    }


    public function testAddBcc2()
    {
        $address1 = ["test1@example.com" => "Example User1"];
        $address2 = ["test2@example.com" => "Example User2"];
        $mailer = new Mailer;
        $mailer->addBcc($address1);
        $mailer->addBcc($address2);
        $this->checkProperty($mailer, "bcc", array_merge($address1, $address2));
    }


    public function testAddContent1()
    {
        $content = "Test Content";
        $mailer = new Mailer;
        $mailer->addContent($content);
        $this->checkProperty($mailer, "content", $content);
    }


    public function testAddContent2()
    {
        $content1 = "Test Content1\n";
        $content2 = "Test Content2\n";
        $mailer = new Mailer;
        $mailer->addContent($content1);
        $mailer->addContent($content2);
        $this->checkProperty($mailer, "content", $content1 . $content2);
    }


    public function testSend()
    {
        $result = (new Mailer)
            ->setSubject("PHPUnit Test")
            ->addContent("Please ignore this message")
            ->send("test@example.com");
        $this->assertSame(1, $result);
    }
}
