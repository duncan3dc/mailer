<?php

namespace duncan3dc\SwiftMailer;

class Mailer extends Email
{

    public function __construct(array $options = null)
    {
        parent::__construct(new Server($options));
    }
}
