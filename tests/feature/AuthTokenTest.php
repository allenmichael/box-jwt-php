<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthTokenTest extends TestCase
{
    #[Test]
    public function i_can_get_auto_token()
    {
        $retVal = $this->boxClient->usersManager->getMe();
        $body   = json_decode($retVal->getBody());

        $this->assertStringStartsWith('AutomationUser_', filter_var($body->login, FILTER_VALIDATE_EMAIL));
        $this->assertNotEmpty($this->authToken);
    }
}