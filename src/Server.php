<?php

namespace duncan3dc\Mailer;

class Server implements ServerInterface
{
    /**
     * @var string $hostname The hostname or ip address of the server to send the message from.
     */
    private $hostname = "localhost";

    /**
     * @var \Swift_Mailer $mailer An instance of the swift mailer class.
     */
    private $mailer;

    /**
     * @var string $username The username to use for smtp authorisation.
     */
    private $username;

    /**
     * @var string $password The password to use for smtp authorisation.
     */
    private $password;

    /**
     * @var string $encryption The type of encryption to use.
     */
    private $encryption;

    /**
     * @var int $port The port to connect the smtp server on.
     */
    private $port;

    /**
     * @var string $returnPath The address to specify as the return path for bounces.
     */
    private $returnPath;


    /**
     * Create a new instance.
     *
     * @param string $hostname The hostname of the server
     * @param int $pot The port to connect to the server on
     */
    public function __construct(string $hostname = null, string $port = null)
    {
        if ($hostname !== null) {
            $this->hostname = $hostname;
        }
        if ($port !== null) {
            $this->port = $port;
        }

        if ($this->hostname === "localhost") {
            $this->encryption = null;
            if ($port === null) {
                $this->port = 25;
            }
        } else {
            $this->encryption = "ssl";
            if ($port === null) {
                $this->port = 465;
            }
        }
    }


    /**
     * Set the credentials used to authorise on the smtp server.
     *
     * @param string $username The username to use
     * @param string $password The password to use
     *
     * @return ServerInterface
     */
    public function withCredentials(string $username, string $password): ServerInterface
    {
        $server = clone $this;

        $server->username = $username;
        $server->password = $password;

        return $server;
    }


    /**
     * Set the encryption method used by this server.
     *
     * @param string $method The encryption method to use
     *
     * @return ServerInterface
     */
    public function withEncryptionMethod(string $method): ServerInterface
    {
        $server = clone $this;

        $server->encryption = $method;

        return $server;
    }


    /**
     * Set the return path used by this server.
     *
     * @param string $path The path to use
     *
     * @return ServerInterface
     */
    public function withReturnPath(string $path): ServerInterface
    {
        $server = clone $this;

        $server->returnPath = $path;

        return $server;
    }


    /**
     * Create a new instance of the Email class using this server.
     *
     * @return EmailInterface
     */
    public function createMessage(): EmailInterface
    {
        return new Email($this);
    }


    /**
     * Get a singleton of the swift mailer class.
     *
     * @return \Swift_Mailer
     */
    private function getMailer(): \Swift_Mailer
    {
        if ($this->mailer) {
            return $this->mailer;
        }

        $smtp = new \Swift_SmtpTransport($this->hostname, $this->port, $this->encryption);

        if ($this->username !== null) {
            $smtp->setUsername($this->username);
        }
        if ($this->password !== null) {
            $smtp->setPassword($this->password);
        }

        $this->mailer = new \Swift_Mailer($smtp);

        return $this->mailer;
    }


    /**
     * Send an email using this server.
     *
     * @param \Swift_Message $message The email message to send.
     *
     * @return int (number of successful recipients)
     */
    public function send(\Swift_Message $message): int
    {
        # Set the bounce return path if one has been specified
        if ($this->returnPath) {
            $message->setReturnPath($this->returnPath);
        }

        return $this->getMailer()->send($message);
    }
}
