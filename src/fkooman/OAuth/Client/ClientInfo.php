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

class ClientInfo
{
    private $clientId;
    private $clientSecret;
    private $authorizeUri;
    private $tokenUri;

    public function __construct($clientId, $clientSecret, $authorizeUri, $tokenUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->authorizeUri = $authorizeUri;
        $this->tokenUri = $tokenUri;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    public function getAuthorizeUri()
    {
        return $this->authorizeUri;
    }

    public function getTokenUri()
    {
        return $this->tokenUri;
    }
}
