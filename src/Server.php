<?php

namespace duncan3dc\SwiftMailer;

class Server
{
    /**
     * @var string $hostname The hostname or ip address of the server to send the message from.
     */
    protected $hostname = "localhost";

    /**
     * @var \Swift_Mailer $mailer An instance of the swift mailer class.
     */
    protected $mailer;

    /**
     * @var string $username The username to use for smtp authorisation.
     */
    protected $username;

    /**
     * @var string $password The password to use for smtp authorisation.
     */
    protected $password;

    /**
     * @var string $encryption The type of encryption to use.
     */
    protected $encryption;

    /**
     * @var int $port The port to connect the smtp server on.
     */
    protected $port;

    /**
     * @var string $returnPath The address to specify as the return path for bounces.
     */
    protected $returnPath;


    /**
     * Create a new instance.
     *
     * @param string $hostname The hostname of the server
     * @param int $pot The port to connect to the server on
     */
    public function __construct($hostname = null, $port = null)
    {
        if ($hostname !== null) {
            $this->hostname = $hostname;
        }
        if ($port !== null) {
            $this->port = $port;
        }

        if ($hostname === "localhost") {
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
     * @return static
     */
    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }


    /**
     * Set the encryption method used by this server.
     *
     * @param string $method The encryption method to use
     *
     * @return static
     */
    public function setEncryptionMethod($method)
    {
        $this->encryption = $method;

        return $this;
    }


    /**
     * Set the return path used by this server.
     *
     * @param string $path The path to use
     *
     * @return static
     */
    public function setReturnPath($path)
    {
        $this->returnPath = $path;

        return $this;
    }


    /**
     * Create a new instance of the Email class using this server.
     *
     * @return Email
     */
    public function createMessage()
    {
        return new Email($this);
    }


    /**
     * Get a singleton of the swift mailer class.
     *
     * @return \Swift_Mailer
     */
    protected function getMailer()
    {
        if ($this->mailer) {
            return $this->mailer;
        }

        $smtp = \Swift_SmtpTransport::newInstance($this->hostname, $this->port, $this->encryption);

        if ($this->username !== null) {
            $smtp->setUsername($this->username);
        }
        if ($this->password !== null) {
            $smtp->setPassword($this->password);
        }

        $this->mailer = \Swift_Mailer::newInstance($smtp);

        return $this->mailer;
    }


    /**
     * Send an email using this server.
     *
     * @param \Swift_Message $message The email message to send.
     *
     * @return int (number of successful recipients)
     */
    public function send(\Swift_Message $message)
    {
        # Set the bounce return path if one has been specified
        if ($this->returnPath) {
            $message->setReturnPath($this->returnPath);
        }

        return $this->getMailer()->send($message);
    }
}
