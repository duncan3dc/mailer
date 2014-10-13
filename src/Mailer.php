<?php

namespace duncan3dc\SwiftMailer;

use duncan3dc\Helpers\Helper;

class Mailer
{
    protected $server;
    protected $username;
    protected $password;

    protected $fromAddress;
    protected $fromName;

    protected $encryption;
    protected $port;
    protected $localPort;

    protected $returnPath;

    protected $to;
    protected $subject;
    protected $content;

    protected $attachments;

    protected $cc;
    protected $bcc;
    protected $replyTo;


    public function __construct($options = null)
    {
        $options = Helper::getOptions($options, [
            "smtpServer"    =>  "",
            "username"      =>  false,
            "password"      =>  false,
            "fromAddress"   =>  "no-reply@example.com",
            "fromName"      =>  "",
            "encryption"    =>  "ssl",
            "port"          =>  465,
            "local-port"    =>  25,
            "returnPath"    =>  false,
        ]);

        $this->server       =   $options["smtpServer"];
        $this->username     =   $options["username"];
        $this->password     =   $options["password"];

        $this->fromAddress  =   $options["fromAddress"];
        $this->fromName     =   $options["fromName"];

        $this->encryption   =   $options["encryption"];
        $this->port         =   $options["port"];
        $this->localPort    =   $options["local-port"];

        $this->returnPath   =   $options["returnPath"];

        $this->to = [];
        $this->subject = "";
        $this->content = "";

        $this->attachments = [];

        $this->cc = [];
        $this->bcc = [];
        $this->replyTo = [];
    }


    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }


    public function setRecipient($address)
    {
        $this->to = [];

        $this->addRecipient($address);

        return $this;
    }


    public function addRecipient($address)
    {
        if (!is_array($address)) {
            $address = [$address => $address];
        }

        $this->to = array_merge($this->to, $address);

        return $this;
    }


    public function setCc($address)
    {
        $this->cc = [];

        $this->addCc($address);

        return $this;
    }


    public function addCc($address)
    {
        if (!is_array($address)) {
            $address = [$address => $address];
        }

        $this->cc = array_merge($this->cc, $address);

        return $this;
    }


    public function setBcc($address)
    {
        $this->bcc = [];

        $this->addBcc($address);

        return $this;
    }


    public function addBcc($address)
    {
        if (!is_array($address)) {
            $address = [$address => $address];
        }

        $this->bcc = array_merge($this->bcc, $address);

        return $this;
    }


    public function setReplyTo($address)
    {
        $this->replyTo = [];

        $this->addReplyTo($address);

        return $this;
    }


    public function addReplyTo($address)
    {
        if (!is_array($address)) {
            $address = [$address => $address];
        }

        $this->replyTo = array_merge($this->replyTo, $address);

        return $this;
    }


    public function addContent($content)
    {
        $this->content .= $content;

        return $this;
    }


    public function addAttachment($path, $filename = null)
    {
        $this->attachments[$path] = $filename;

        return $this;
    }


    public function send($address = null)
    {
        if ($address) {
            $this->addRecipient($address);
        }
        if (count($this->to) < 1) {
            throw new \Exception("No recipients specified to send the email to");
        }
        $keys = array_keys($this->to);
        if (!$keys[0]) {
            throw new \Exception("Invalid recipient specified to send the email to");
        }

        # Connect to the smtp server
        if ($this->server) {
            $smtp = \Swift_SmtpTransport::newInstance($this->server, $this->port, $this->encryption);
        } else {
            $smtp = \Swift_SmtpTransport::newInstance("localhost", $this->localPort);
        }
        if ($this->username) {
            $smtp->setUsername($this->username);
        }
        if ($this->password) {
            $smtp->setPassword($this->password);
        }

        # Create a new instance of the swift mailer
        $swift = \Swift_Mailer::newInstance($smtp);

        # Create a new message
        $mail = \Swift_Message::newInstance();

        # Set the bounce return path if one has been specified
        if ($this->returnPath) {
            $mail->setReturnPath($this->returnPath);
        }

        $html = "<html>";
            $html .= "<head>";
                $html .= "<style type='text/css'>";
                    $html .= "body { margin:0px; font-family:arial,helvetica,sans-serif; font-size:13px; }";
                $html .= "</style>";
            $html .= "</head>";
            $html .= "<body>";
                $html .= $this->content;
            $html .= "</body>";
        $html .= "</html>";

        # Give the message a subject
        $mail->setSubject($this->subject);

        # Set the from address
        $mail->setFrom([$this->fromAddress => $this->fromName]);

        # Set the to address
        $mail->setTo($this->to);

        # Add the html body and an alternative plain text version
        $mail->setBody($html, "text/html");
        $mail->addPart("To view this message, please use an HTML compatible email viewer.", "text/plain");

        # If attachments have been specified then attach them
        foreach ($this->attachments as $path => $filename) {
            $attachment = \Swift_Attachment::fromPath($path);
            if ($filename) {
                $attachment->setFilename($filename);
            }
            $mail->attach($attachment);
        }

        # Set the relevant cc
        if (count($this->cc) > 0) {
            $mail->setCc($this->cc);
        }

        # Set the relevant bcc
        if (count($this->bcc) > 0) {
            $mail->setBcc($this->bcc);
        }

        # Set the relevant reply-to
        if (count($this->replyTo) > 0) {
            $mail->setReplyTo($this->replyTo);
        }

        # Send the message
        $result = $swift->send($mail);

        return $result;
    }
}
