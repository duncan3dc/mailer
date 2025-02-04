<?php

namespace duncan3dc\Mailer;

interface EmailInterface
{

    /**
     * Set the address to send emails from.
     *
     * @param string $address The address to send the message from
     * @param string $name The name to send the message from
     *
     * @return EmailInterface
     */
    public function withFromAddress(string $address, string $name = null): EmailInterface;


    /**
     * Set the subject of the message.
     *
     * @param string $subject The subject to use
     *
     * @return EmailInterface
     */
    public function withSubject(string $subject): EmailInterface;


    /**
     * Add a recipient to the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function withRecipient(string $address, string $name = null): EmailInterface;


    /**
     * Remove any recipients previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutRecipients(): EmailInterface;


    /**
     * Add a cc to the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function withCc(string $address, string $name = null): EmailInterface;


    /**
     * Remove any cc addresses previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutCc(): EmailInterface;


    /**
     * Add a bcc to the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function withBcc(string $address, string $name = null): EmailInterface;


    /**
     * Remove any bcc addresses previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutBcc(): EmailInterface;


    /**
     * Set the reply to address for the message.
     *
     * @param string $address The email address of the recipient
     * @param string $name The name of the recipient
     *
     * @return EmailInterface
     */
    public function withReplyTo(string $address, string $name = null): EmailInterface;


    /**
     * Remove the reply to address previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutReplyTo(): EmailInterface;


    /**
     * Add content to the body of the message.
     *
     * @param string $content The html content to add
     *
     * @return EmailInterface
     */
    public function withContent(string $content): EmailInterface;


    /**
     * Add content to the body of the message.
     *
     * @param string $view The name of the view to use
     * @param array $params Parameters to pass to the view
     *
     * @return EmailInterface
     */
    public function withView(string $view, array $params = null): EmailInterface;


    /**
     * Remove any content previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutContent(): EmailInterface;


    /**
     * Add an attachment to the message.
     *
     * @param string $path The full path to the file to attach
     * @param string $filename An optional filename to use (instead of the filename from the path)
     *
     * @return EmailInterface
     */
    public function withAttachment(string $path, string $filename = null): EmailInterface;


    /**
     * Remove any attachments previously applied to the message.
     *
     * @return EmailInterface
     */
    public function withoutAttachments(): EmailInterface;


    /**
     * Send the message.
     */
    public function send(): void;
}
