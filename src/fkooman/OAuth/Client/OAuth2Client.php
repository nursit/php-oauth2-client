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

use fkooman\OAuth\Client\Exception\OAuthException;

class OAuth2Client
{
    /** @var Provider */
    private $provider;

    /** @var HttpClientInterface */
    private $httpClient;

    /** @var RandomInterface */
    private $random;

    public function __construct(Provider $provider, HttpClientInterface $httpClient, RandomInterface $random = null)
    {
        $this->provider = $provider;
        $this->httpClient = $httpClient;
        if (is_null($random)) {
            $random = new Random();
        }
        $this->random = $random;
    }

    public function getAuthorizationRequestUri($scope, $redirectUri)
    {
        $state = $this->random->get();

        $queryParams = http_build_query(
            [
                'client_id' => $this->provider->getId(),
                'redirect_uri' => $redirectUri,
                'scope' => $scope,
                'state' => $state,
                'response_type' => 'code',
            ],
            '&'
        );

        return sprintf(
            '%s%s%s',
            $this->provider->getAuthorizationEndpoint(),
            false === strpos($this->provider->getAuthorizationEndpoint(), '?') ? '?' : '&',
            $queryParams
        );
    }

    public function getAccessToken($authorizationRequestUri, $authorizationResponseCode, $authorizationResponseState)
    {
        // parse our authorizationRequestUri to extract the state
        if (false === strpos($authorizationRequestUri, '?')) {
            throw new OAuthException('invalid authorizationRequestUri');
        }

        parse_str(explode('?', $authorizationRequestUri)[1], $queryParams);

        if (!isset($queryParams['state'])) {
            throw new OAuthException('state missing from authorizationRequestUri');
        }

        if (!isset($queryParams['redirect_uri'])) {
            throw new OAuthException('redirect_uri missing from authorizationRequestUri');
        }

        if ($authorizationResponseState !== $queryParams['state']) {
            throw new OAuthException('state from authorizationRequestUri MUST match authorizationResponseState');
        }

        // prepare access_token request
        $tokenRequestData = [
            'client_id' => $this->provider->getId(),
            'grant_type' => 'authorization_code',
            'code' => $authorizationResponseCode,
            'redirect_uri' => $queryParams['redirect_uri'],
        ];

        $responseData = $this->httpClient->post(
            $this->provider,
            $tokenRequestData
        );

        if (!isset($responseData['access_token'])) {
            throw new OAuthException('no access_token received from token endpoint');
        }

        if (!isset($responseData['token_type'])) {
            throw new OAuthException('no token_type received from token endpoint');
        }

        $scope = null;
        if (isset($responseData['scope'])) {
            $scope = $responseData['scope'];
        }

        $expiresIn = null;
        if (isset($responseData['expires_in'])) {
            $expiresIn = $responseData['expires_in'];
        }

        return new AccessToken(
            $responseData['access_token'],
            $responseData['token_type'],
            $scope,
            $expiresIn
        );
    }
}
