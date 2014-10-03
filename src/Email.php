<?php

namespace duncan3dc\SwiftMailer;

use duncan3dc\Helpers\Helper;
use duncan3dc\Laravel\Blade;

class Email
{
    /**
     * @var Server $server The server instance to use to send the email
     */
    protected $server;

    /**
     * @var array $to The addresses to send the message to
     */
    protected $to = [];

    /**
     * @var string $subject The subject to put on the message
     */
    protected $subject = "";

    /**
     * @var string $content The html content to include in the message
     */
    protected $content = "";

    /**
     * @var array $attachments Any attachments to include in the message
     */
    protected $attachments = [];

    /**
     * @var array $cc The addresses to cc on the message
     */
    protected $cc = [];

    /**
     * @var array $bcc The addresses to bcc on the message
     */
    protected $bcc = [];

    /**
     * @var array $replyTo The addresses to use as the reply to for the message
     */
    protected $replyTo = [];


    public function __construct(Server $server)
    {
        $this->server = $server;
    }


    /**
     * Set the subject of the message, discarding any previously set subject.
     *
     * @param string $subject The subject to use
     *
     * @return static
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }


    /**
     * Set the recipient of the message, discarding any previously defined recipients.
     *
     * @param string|array $address An address, either as a string of just the email address, or an array where the key is the address and the value is the recipient's name
     *
     * @return static
     */
    public function setRecipient($address)
    {
        $this->to = [];

        $this->addRecipient($address);

        return $this;
    }


    /**
     * Add a recipient to the message.
     *
     * @param string|array $address An address, either as a string of just the email address, or an array where the key is the address and the value is the recipient's name
     *
     * @return static
     */
    public function addRecipient($address)
    {
        if (!is_array($address)) {
            $address = [$address => $address];
        }

        $this->to = array_merge($this->to, $address);

        return $this;
    }


    /**
     * Set the cc for the message, discarding any previously defined cc addresses.
     *
     * @param string|array $address An address, either as a string of just the email address, or an array where the key is the address and the value is the recipient's name
     *
     * @return static
     */
    public function setCc($address)
    {
        $this->cc = [];

        $this->addCc($address);

        return $this;
    }


    /**
     * Add a cc to the message.
     *
     * @param string|array $address An address, either as a string of just the email address, or an array where the key is the address and the value is the recipient's name
     *
     * @return static
     */
    public function addCc($address)
    {
        if (!is_array($address)) {
            $address = [$address => $address];
        }

        $this->cc = array_merge($this->cc, $address);

        return $this;
    }


    /**
     * Set the bcc for the message, discarding any previously defined bcc addresses.
     *
     * @param string|array $address An address, either as a string of just the email address, or an array where the key is the address and the value is the recipient's name
     *
     * @return static
     */
    public function setBcc($address)
    {
        $this->bcc = [];

        $this->addBcc($address);

        return $this;
    }


    /**
     * Add a bcc to the message.
     *
     * @param string|array $address An address, either as a string of just the email address, or an array where the key is the address and the value is the recipient's name
     *
     * @return static
     */
    public function addBcc($address)
    {
        if (!is_array($address)) {
            $address = [$address => $address];
        }

        $this->bcc = array_merge($this->bcc, $address);

        return $this;
    }


    /**
     * Set the reply to address for the message, discarding any previously defined reply to addresses.
     *
     * @param string|array $address An address, either as a string of just the email address, or an array where the key is the address and the value is the recipient's name
     *
     * @return static
     */
    public function setReplyTo($address)
    {
        $this->replyTo = [];

        $this->addReplyTo($address);

        return $this;
    }


    /**
     * Add a reply to address to the message.
     *
     * @param string|array $address An address, either as a string of just the email address, or an array where the key is the address and the value is the recipient's name
     *
     * @return static
     */
    public function addReplyTo($address)
    {
        if (!is_array($address)) {
            $address = [$address => $address];
        }

        $this->replyTo = array_merge($this->replyTo, $address);

        return $this;
    }


    /**
     * Set the content of the body of the message, discarding any previously added content.
     *
     * @param string $content The html content to add
     *
     * @return static
     */
    public function setContent($content)
    {
        $this->content = "";
        return $this->addContent($content);
    }


    /**
     * Add content to the body of the message.
     *
     * @param string $content The html content to add
     *
     * @return static
     */
    public function addContent($content)
    {
        $this->content .= $content;

        return $this;
    }


    /**
     * Set the content of the body of the message, discarding any previously added content.
     *
     * @param string $view The name of the view to use
     * @param array $params Parameters to pass to the view
     *
     * @return static
     */
    public function setView($view, array $params = null)
    {
        $this->content = "";
        return $this->addView($view, $params);
    }


    /**
     * Add content to the body of the message.
     *
     * @param string $view The name of the view to use
     * @param array $params Parameters to pass to the view
     *
     * @return static
     */
    public function addView($view, array $params = null)
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
     * @return static
     */
    public function addAttachment($path, $filename = null)
    {
        $this->attachments[$path] = $filename;

        return $this;
    }


    /**
     * Send the message.
     *
     * @param string|array $address An additional to address for the message, either as a string of just the email address, or an array where the key is the address and the value is the recipient's name
     *
     * @return int (number of successful recipients)
     */
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

        $message = \Swift_Message::newInstance();

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

        # Send the message
        return $this->server->send($message);
    }
}
