<?php

namespace duncan3dc;

class Mailer {

    public  $server;
    public  $fromAddress;
    public  $fromName;

    public  $content;

    public  $to;
    public  $subject;

    public  $attachments;

    public  $cc;
    public  $bcc;


    public function __construct($options=false) {

        $defaults = [
            "smtpServer"    =>  "",
            "username"      =>  false,
            "password"      =>  false,
            "fromAddress"   =>  "no-reply@example.com",
            "fromName"      =>  "",
        ];
        if(is_array($options)) {
            foreach($options as $key => $val) {
                if(!array_key_exists($key,$defaults)) {
                    throw new \Exception("Unknown parameter (" . $key . ")");
                }
                $defaults[$key] = $val;
            }
        }
        $options = $defaults;

        $this->server = $options["smtpServer"];
        $this->username = $options["username"];
        $this->password = $options["password"];
        $this->fromAddress = $options["fromAddress"];
        $this->fromName = $options["fromName"];

        $this->to = [];
        $this->subject = "";
        $this->content = "";

        $this->attachments = [];

        $this->cc = [];
        $this->bcc = [];
        $this->replyTo = [];

    }


    public function setSubject($subject) {

        $this->subject = $subject;

    }


    public function setRecipient($address) {

        $this->to = [];

        $this->addRecipient($address);

    }


    public function addRecipient($address) {

        if(!is_array($address)) {
            $address = [$address => $address];
        }

        $this->to = array_merge($this->to,$address);

    }


    public function setCc($address) {

        $this->cc = [];

        $this->addCc($address);

    }


    public function addCc($address) {

        if(!is_array($address)) {
            $address = [$address => $address];
        }

        $this->cc = array_merge($this->cc,$address);

    }


    public function setBcc($address) {

        $this->bcc = [];

        $this->addBcc($address);

    }


    public function addBcc($address) {

        if(!is_array($address)) {
            $address = [$address => $address];
        }

        $this->bcc = array_merge($this->bcc,$address);

    }


    public function setReplyTo($address) {

        $this->replyTo = [];

        $this->addReplyTo($address);

    }


    public function addReplyTo($address) {

        if(!is_array($address)) {
            $address = [$address => $address];
        }

        $this->replyTo = array_merge($this->replyTo,$address);

    }


    public function addContent($content) {

        $this->content .= $content;

    }


    public function addAttachment($path,$filename=false) {

        $this->attachments[$path] = $filename;

    }


    public function send() {

        if(count($this->to) < 1) {
            throw new \Exception("No recipients specified to send the email to");
        }
        $keys = array_keys($this->to);
        if(!$keys[0]) {
            throw new \Exception("Invalid recipient specified to send the email to");
        }

        # Connect to the smtp server
        if($this->server) {
            $smtp = \Swift_SmtpTransport::newInstance($this->server,465,"ssl");
        } else {
            $smtp = \Swift_SmtpTransport::newInstance("localhost",25);
        }
        if($this->username) {
            $smtp->setUsername($this->username);
        }
        if($this->password) {
            $smtp->setPassword($this->password);
        }

        # Create a new instance of the swift mailer
        $swift = \Swift_Mailer::newInstance($smtp);

        # Create a new message
        $mail = \Swift_Message::newInstance();

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
        $mail->setBody($html,"text/html");
        $mail->addPart("To view this message, please use an HTML compatible email viewer.","text/plain");

        # If attachments have been specified then attach them
        foreach($this->attachments as $path => $filename) {
            $attachment = \Swift_Attachment::fromPath($path);
            if($filename) {
                $attachment->setFilename($filename);
            }
            $mail->attach($attachment);
        }

        # Set the relevant cc
        if(count($this->cc) > 0) {
            $mail->setCc($this->cc);
        }

        # Set the relevant bcc
        if(count($this->bcc) > 0) {
            $mail->setBcc($this->bcc);
        }

        # Set the relevant reply-to
        if(count($this->replyTo) > 0) {
            $mail->setReplyTo($this->replyTo);
        }

        # Send the message
        $result = $swift->send($mail);

        return $result;

    }


}
