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
use GuzzleHttp\Exception\RequestException;

class GuzzleHttpClient implements HttpClientInterface
{
    public function post(Provider $provider, array $postData)
    {
        $client = new Client();
        try {
            $httpResponse = $client->post(
                $provider->getTokenEndpoint(),
                [
                    'auth' => [$provider->getId(), $provider->getSecret()],
                    'body' => $postData,
                ]
            );

            return $httpResponse->json();
        } catch (RequestException $e) {
            // error occured when trying to retrieve the access token, ignore
            // this, we can't do anything about it!
            return [];
        }
    }
}
