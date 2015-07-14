<?php

namespace duncan3dc\SwiftMailer;

class Mailer extends Email
{

    public function __construct(array $options = null)
    {
        if (!is_array($options)) {
            $options = [];
        }

        if (empty($options["smtpServer"])) {
            $hostname = "localhost";
        } else {
            $hostname = $options["smtpServer"];
        }

        if ($hostname === "localhost") {
            $port = isset($options["local-port"]) ? $options["local-port"] : 25;
        } else {
            $port = isset($options["port"]) ? $options["port"] : 465;
        }

        $server = new Server($hostname, $port);

        if (!empty($options["username"]) || !empty($options["password"])) {
            $server->setCredentials($options["username"], $options["password"]);
        }

        if (!empty($options["encryption"])) {
            $server->setEncryptionMethod($options["encryption"]);
        }

        if (!empty($options["returnPath"])) {
            $server->setReturnPath($options["returnPath"]);
        }

        parent::__construct($server);

        if (!empty($options["fromAddress"])) {
            $this->setFromAddress($options["fromAddress"], $options["fromName"]);
        }
    }
}
