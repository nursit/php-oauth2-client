<?php
/**
 * Copyright 2016 François Kooman <fkooman@tuxed.net>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace fkooman\OAuth\Client;

class AccessToken
{
    /** @var string */
    private $token;

    /** @var string */
    private $tokenType;

    /** @var string */
    private $scope;

    /** @var int */
    private $expiresIn;

    public function __construct($token, $tokenType, $scope, $expiresIn)
    {
        $this->token = $token;
        $this->tokenType = $tokenType;
        $this->scope = $scope;
        $this->expiresIn = $expiresIn;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getTokenType()
    {
        return $this->tokenType;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    public function __toString()
    {
        return $this->getToken();
    }
}
