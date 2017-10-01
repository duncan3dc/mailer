<?php

namespace duncan3dc\Mailer;

interface ServerInterface
{
    /**
     * Set the credentials used to authorise on the smtp server.
     *
     * @param string $username The username to use
     * @param string $password The password to use
     *
     * @return ServerInterface
     */
    public function setCredentials(string $username, string $password): ServerInterface;


    /**
     * Set the encryption method used by this server.
     *
     * @param string $method The encryption method to use
     *
     * @return ServerInterface
     */
    public function setEncryptionMethod(string $method): ServerInterface;


    /**
     * Set the return path used by this server.
     *
     * @param string $path The path to use
     *
     * @return ServerInterface
     */
    public function setReturnPath(string $path): ServerInterface;


    /**
     * Create a new instance of the Email class using this server.
     *
     * @return EmailInterface
     */
    public function createMessage(): EmailInterface;


    /**
     * Send an email using this server.
     *
     * @param \Swift_Message $message The email message to send.
     *
     * @return int (number of successful recipients)
     */
    public function send(\Swift_Message $message): int;
}
