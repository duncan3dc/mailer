<?php

namespace duncan3dc\SwiftMailer;

use duncan3dc\Helpers\Helper;

class Server
{
    /**
     * @var string $hostname The hostname or ip address of the server to send the message from
     */
    protected $hostname;

    /**
     * @var \Swift_Mailer $mailer An instance of the swift mailer class
     */
    protected $mailer;

    /**
     * @var string $username The username to use for smtp authorisation
     */
    protected $username;

    /**
     * @var string $password The password to use for smtp authorisation
     */
    protected $password;

    /**
     * @var string $fromAddress The address to send the message from
     */
    protected $fromAddress;

    /**
     * @var string $fromName The name to send the message from
     */
    protected $fromName;

    /**
     * @var string $encryption The type of encryption to use
     */
    protected $encryption;

    /**
     * @var int $port The port to connect the smtp server on
     */
    protected $port;

    /**
     * @var int $localPort The port to connect to the local smtp server on
     */
    protected $localPort;

    /**
     * @var string $returnPath The address to specify as the return path for bounces
     */
    protected $returnPath;


    public function __construct($options = null)
    {
        $options = Helper::getOptions($options, [
            "hostname"      =>  "",
            "username"      =>  false,
            "password"      =>  false,
            "fromAddress"   =>  "no-reply@example.com",
            "fromName"      =>  "",
            "encryption"    =>  "ssl",
            "port"          =>  465,
            "local-port"    =>  25,
            "returnPath"    =>  false,
        ]);

        $this->hostname     =   $options["hostname"];
        $this->username     =   $options["username"];
        $this->password     =   $options["password"];

        $this->fromAddress  =   $options["fromAddress"];
        $this->fromName     =   $options["fromName"];

        $this->encryption   =   $options["encryption"];
        $this->port         =   $options["port"];
        $this->localPort    =   $options["local-port"];

        $this->returnPath   =   $options["returnPath"];
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

        if ($this->hostname) {
            $smtp = \Swift_SmtpTransport::newInstance($this->hostname, $this->port, $this->encryption);
        } else {
            $smtp = \Swift_SmtpTransport::newInstance("localhost", $this->localPort);
        }
        if ($this->username) {
            $smtp->setUsername($this->username);
        }
        if ($this->password) {
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
        # Set the from address
        $message->setFrom([$this->fromAddress => $this->fromName]);

        # Set the bounce return path if one has been specified
        if ($this->returnPath) {
            $message->setReturnPath($this->returnPath);
        }

        return $this->getMailer()->send($message);
    }
}
