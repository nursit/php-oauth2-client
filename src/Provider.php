<?php
/**
 *  Copyright (C) 2016 François Kooman <fkooman@tuxed.net>.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace fkooman\OAuth\Client;

/**
 * OAuth 2.0 provider definition.
 */
class Provider
{
    /** @var string */
    private $clientId;

    /** @var string */
    private $clientSecret;

    /** @var string */
    private $authorizationEndpoint;

    /** @var string */
    private $tokenEndpoint;

    /**
     * Instantiate an OAuth 2.0 provider.
     *
     * @param string $clientId              client id
     * @param string $clientSecret          the client secret
     * @param string $authorizationEndpoint the authorization endpoint
     * @param string $tokenEndpoint         the token endpoint
     */
    public function __construct($clientId, $clientSecret, $authorizationEndpoint, $tokenEndpoint)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->authorizationEndpoint = $authorizationEndpoint;
        $this->tokenEndpoint = $tokenEndpoint;
    }

    /**
     * Get the client id.
     *
     * @return string the client id
     *
     * @see https://tools.ietf.org/html/rfc6749#section-2.2
     */
    public function getId()
    {
        return $this->clientId;
    }

    /**
     * Get the client secret.
     *
     * @return string the client secret
     *
     * @see https://tools.ietf.org/html/rfc6749#section-2.3.1
     */
    public function getSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Get the authorization endpoint.
     *
     * @return string the authorization endpoint
     *
     * @see https://tools.ietf.org/html/rfc6749#section-3.1
     */
    public function getAuthorizationEndpoint()
    {
        return $this->authorizationEndpoint;
    }

    /**
     * Get the token endpoint.
     *
     * @return string the token endpoint
     *
     * @see https://tools.ietf.org/html/rfc6749#section-3.2
     */
    public function getTokenEndpoint()
    {
        return $this->tokenEndpoint;
    }
}
