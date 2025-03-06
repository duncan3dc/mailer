<?php

namespace duncan3dc\Mailer;

use duncan3dc\Laravel\Blade;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Exception\RfcComplianceException;
use Symfony\Component\Mime\Part\File;

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
     * @var array<Address> $to The addresses to send the message to
     */
    private $to = [];

    /**
     * @var array<Address> $cc The addresses to cc on the message
     */
    private $cc = [];

    /**
     * @var array<Address> $bcc The addresses to bcc on the message
     */
    private $bcc = [];

    /**
     * @var Address|null $replyTo The address to use as the reply to for the message
     */
    private $replyTo = null;

    /**
     * @var ?Address $from The address to send the message from.
     */
    private $from;


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
     * Clone the instance and update one of its properties.
     *
     * @param string $property The name of the property to update
     * @param mixed $value The value to set the property to
     *
     * @return EmailInterface
     */
    private function clone(string $property, $value): EmailInterface
    {
        $email = clone $this;

        $email->$property = $value;

        return $email;
    }


    private function address(string $address, ?string $name): Address
    {
        try {
            return new Address($address, $name ?? "");
        } catch (RfcComplianceException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * Set the address to send emails from.
     *
     * @param string $address The address to send the message from
     * @param string $name The name to send the message from
     *
     * @return EmailInterface
     */
    public function withFromAddress(string $address, ?string $name = null): EmailInterface
    {
        return $this->clone("from", $this->address($address, $name));
    }


    /**
     * Set the subject of the message.
     *
     * @param string $subject The subject to use
     *
     * @return EmailInterface
     */
    public function withSubject(string $subject): EmailInterface
    {
        return $this->clone("subject", $subject);
    }


    /**
     * Add a recipient to the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function withRecipient(string $address, ?string $name = null): EmailInterface
    {
        $email = clone $this;

        $email->to[] = $this->address($address, $name);

        return $email;
    }


    /**
     * Remove any recipients previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutRecipients(): EmailInterface
    {
        return $this->clone("to", []);
    }


    /**
     * Add a cc to the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function withCc(string $address, ?string $name = null): EmailInterface
    {
        $email = clone $this;

        $email->cc[] = $this->address($address, $name);

        return $email;
    }


    /**
     * Remove any cc addresses previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutCc(): EmailInterface
    {
        return $this->clone("cc", []);
    }


    /**
     * Add a bcc to the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function withBcc(string $address, ?string $name = null): EmailInterface
    {
        $email = clone $this;

        $email->bcc[] = $this->address($address, $name);

        return $email;
    }


    /**
     * Remove any bcc addresses previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutBcc(): EmailInterface
    {
        return $this->clone("bcc", []);
    }


    /**
     * Set the reply to address for the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function withReplyTo(string $address, ?string $name = null): EmailInterface
    {
        return $this->clone("replyTo", $this->address($address, $name));
    }


    /**
     * Remove the reply to address previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutReplyTo(): EmailInterface
    {
        return $this->clone("replyTo", null);
    }


    /**
     * Add content to the body of the message.
     *
     * @param string $content The html content to add
     *
     * @return EmailInterface
     */
    public function withContent(string $content): EmailInterface
    {
        return $this->clone("content", $this->content . $content);
    }


    /**
     * Add content to the body of the message.
     *
     * @param string $view The name of the view to use
     * @param array $params Parameters to pass to the view
     *
     * @return EmailInterface
     */
    public function withView(string $view, ?array $params = null): EmailInterface
    {
        if (!is_array($params)) {
            $params = [];
        }

        $content = Blade::render($view, $params);

        return $this->withContent($content);
    }


    /**
     * Remove any content previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutContent(): EmailInterface
    {
        return $this->clone("content", "");
    }


    /**
     * Add an attachment to the message.
     *
     * @param string $path The full path to the file to attach
     * @param string $filename An optional filename to use (instead of the filename from the path)
     *
     * @return EmailInterface
     */
    public function withAttachment(string $path, ?string $filename = null): EmailInterface
    {
        $email = clone $this;

        $email->attachments[$path] = $filename;

        return $email;
    }


    /**
     * Remove any attachments previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutAttachments(): EmailInterface
    {
        return $this->clone("attachments", []);
    }


    /**
     * Send the message.
     */
    public function send(): void
    {
        if (count($this->to) < 1) {
            throw new Exception("No recipients specified to send the email to");
        }

        $message = new \Symfony\Component\Mime\Email();

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
        $message->subject($this->subject);

        # Set the to address
        $message->to(...$this->to);

        # Add the html body and an alternative plain text version
        $message->html($html);
        $message->text("To view this message, please use an HTML compatible email viewer.");

        # If attachments have been specified then attach them
        foreach ($this->attachments as $path => $filename) {
            $message->attachFromPath($path, $filename ?: null);
        }

        # Set the relevant cc
        if (count($this->cc) > 0) {
            $message->cc(...$this->cc);
        }

        # Set the relevant bcc
        if (count($this->bcc) > 0) {
            $message->bcc(...$this->bcc);
        }

        # Set the relevant reply-to
        if ($this->replyTo !== null) {
            $message->replyTo($this->replyTo);
        }

        # Set the from address
        $message->from($this->from ?? new Address("no-reply@example.com"));

        # Send the message
        $this->server->send($message);
    }
}
