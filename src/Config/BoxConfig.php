<?php

namespace Box\Config;

use Box\Exceptions\BoxSdkException;

class BoxConfig
{
    public $clientId;
    public $clientSecret;
    public $enterpriseId;
    public $jwtPrivateKey;
    public $jwtPrivateKeyPassword;
    public $jwtPublicKeyId;

    /**
     * Create new BoxConfig instance.
     *
     * The $arg array passed in must have the following required fields:
     *     - clientId
     *     - clientSecret
     *     - enterpriseId
     *     - jwtPrivateKey
     *     - jwtPrivateKeyPassword
     *     - jwtPublicKeyId
     *
     * @param string[] $args
     *
     * @throws \Box\Exceptions\BoxSdkException
     */
    function __construct($args)
    {
        $requiredFields = [
            "clientId",
            "clientSecret",
            "enterpriseId",
            "jwtPrivateKey",
            "jwtPrivateKeyPassword",
            "jwtPublicKeyId",
        ];

        // Check for required fields
        $missingRequiredFields = array_diff($requiredFields, array_keys($args));

        if (!empty($missingRequiredFields)) {
            throw new BoxSdkException('Required config field(s) missing: ' . implode(', ', $missingRequiredFields));
        }

        // Initialize
        $this->clientId              = $args["clientId"];
        $this->clientSecret          = $args["clientSecret"];
        $this->enterpriseId          = $args["enterpriseId"];
        $this->jwtPrivateKey         = $args["jwtPrivateKey"];
        $this->jwtPrivateKeyPassword = $args["jwtPrivateKeyPassword"];
        $this->jwtPublicKeyId        = $args["jwtPublicKeyId"];
    }
}