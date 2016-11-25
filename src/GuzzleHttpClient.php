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

use GuzzleHttp\Client;

class GuzzleHttpClient implements HttpClientInterface
{
    /** @var \GuzzleHttp\Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function post(Provider $provider, array $postData)
    {
        $httpResponse = $this->client->post(
            $provider->getTokenEndpoint(),
            [
                'auth' => [
                    $provider->getId(),
                    $provider->getSecret(),
                ],
                'body' => $postData,
            ]
        );

        return $httpResponse->json();
    }
}
