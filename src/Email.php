<?php

namespace duncan3dc\Mailer;

use duncan3dc\Laravel\Blade;

class Email implements EmailInterface
{
    /**
     * @var Server $server The server instance to use to send the email
     */
    private $server;

    /**
     * @var string $subject The subject to put on the message
     */
    private $subject = "";

    /**
     * @var string $content The html content to include in the message
     */
    private $content = "";

    /**
     * @var array $attachments Any attachments to include in the message
     */
    private $attachments = [];

    /**
     * @var array $to The addresses to send the message to
     */
    private $to = [];

    /**
     * @var array $cc The addresses to cc on the message
     */
    private $cc = [];

    /**
     * @var array $bcc The addresses to bcc on the message
     */
    private $bcc = [];

    /**
     * @var array $replyTo The address to use as the reply to for the message
     */
    private $replyTo = [];

    /**
     * @var string $fromAddress The address to send the message from.
     */
    private $fromAddress = "no-reply@example.com";

    /**
     * @var string $fromName The name to send the message from.
     */
    private $fromName = "";


    /**
     * Create a new instance of the Email class using the specified server.
     *
     * @param Server $server The Server to send emails using
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }


    /**
     * Set the address to send emails from.
     *
     * @param string $address The address to send the message from
     * @param string $name The name to send the message from
     *
     * @return EmailInterface
     */
    public function setFromAddress(string $address, string $name = null): EmailInterface
    {
        $this->fromAddress = $address;
        if ($name !== null) {
            $this->fromName = $name;
        }

        return $this;
    }


    /**
     * Set the subject of the message.
     *
     * @param string $subject The subject to use
     *
     * @return EmailInterface
     */
    public function setSubject(string $subject): EmailInterface
    {
        $this->subject = $subject;

        return $this;
    }


    /**
     * Add a recipient to the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function addRecipient(string $address, string $name = null): EmailInterface
    {
        $this->to[$address] = $name ?? $address;

        return $this;
    }


    /**
     * Add a cc to the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function addCc(string $address, string $name = null): EmailInterface
    {
        $this->cc[$address] = $name ?? $address;

        return $this;
    }


    /**
     * Add a bcc to the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function addBcc(string $address, string $name = null): EmailInterface
    {
        $this->bcc[$address] = $name ?? $address;

        return $this;
    }


    /**
     * Set the reply to address for the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function setReplyTo(string $address, string $name = null): EmailInterface
    {
        $this->replyTo = [
            $address    =>  $name ?? $address,
        ];

        return $this;
    }


    /**
     * Add content to the body of the message.
     *
     * @param string $content The html content to add
     *
     * @return EmailInterface
     */
    public function addContent(string $content): EmailInterface
    {
        $this->content .= $content;

        return $this;
    }


    /**
     * Add content to the body of the message.
     *
     * @param string $view The name of the view to use
     * @param array $params Parameters to pass to the view
     *
     * @return EmailInterface
     */
    public function addView(string $view, array $params = null): EmailInterface
    {
        if (!is_array($params)) {
            $params = [];
        }
        $content = Blade::make($view, $params);
        return $this->addContent($content);
    }


    /**
     * Add an attachment to the message.
     *
     * @param string $path The full path to the file to attach
     * @param string $filename An optional filename to use (instead of the filename from the path)
     *
     * @return EmailInterface
     */
    public function addAttachment(string $path, string $filename = null): EmailInterface
    {
        $this->attachments[$path] = $filename;

        return $this;
    }


    /**
     * Send the message.
     *
     * @return int (number of successful recipients)
     */
    public function send(): int
    {
        if (count($this->to) < 1) {
            throw new \Exception("No recipients specified to send the email to");
        }
        $keys = array_keys($this->to);
        if (!$keys[0]) {
            throw new \Exception("Invalid recipient specified to send the email to");
        }

        $message = new \Swift_Message;

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
        $message->setSubject($this->subject);

        # Set the to address
        $message->setTo($this->to);

        # Add the html body and an alternative plain text version
        $message->setBody($html, "text/html");
        $message->addPart("To view this message, please use an HTML compatible email viewer.", "text/plain");

        # If attachments have been specified then attach them
        foreach ($this->attachments as $path => $filename) {
            $attachment = \Swift_Attachment::fromPath($path);
            if ($filename) {
                $attachment->setFilename($filename);
            }
            $message->attach($attachment);
        }

        # Set the relevant cc
        if (count($this->cc) > 0) {
            $message->setCc($this->cc);
        }

        # Set the relevant bcc
        if (count($this->bcc) > 0) {
            $message->setBcc($this->bcc);
        }

        # Set the relevant reply-to
        if (count($this->replyTo) > 0) {
            $message->setReplyTo($this->replyTo);
        }

        # Set the from address
        $message->setFrom([$this->fromAddress => $this->fromName]);

        # Send the message
        return $this->server->send($message);
    }
}
