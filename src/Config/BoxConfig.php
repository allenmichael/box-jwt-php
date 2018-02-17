<?php

namespace Box\Config;
class BoxConfig
{
    public $clientId;
    public $clientSecret;
    public $enterpriseId;
    public $jwtPrivateKey;
    public $jwtPrivateKeyPassword;
    public $jwtPublicKeyId;

    function __construct($args)
    {
        $this->clientId              = $args["clientId"];
        $this->clientSecret          = $args["clientSecret"];
        $this->enterpriseId          = $args["enterpriseId"];
        $this->jwtPrivateKey         = $args["jwtPrivateKey"];
        $this->jwtPrivateKeyPassword = $args["jwtPrivateKeyPassword"];
        $this->jwtPublicKeyId        = $args["jwtPublicKeyId"];
    }
}