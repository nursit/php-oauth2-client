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

use RuntimeException;

/**
 * Retrieve an access token using the cURL HTTP client.
 */
class CurlHttpClient implements HttpClientInterface
{
    public function post(Provider $provider, array $postData)
    {
        $ch = curl_init($provider->getTokenEndpoint());

        $optionsSet = curl_setopt_array(
            $ch,
            [
                CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => sprintf('%s:%s', $provider->getId(), $provider->getSecret()),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData,
                CURLOPT_RETURNTRANSFER => true,
            ]
        );

        if (!$optionsSet) {
            throw new RuntimeException('unable to set all cURL options');
        }

        $jsonResponse = curl_exec($ch);
        curl_close($ch);
        if (false === $jsonResponse) {
            return [];
        }

        $response = json_decode($jsonResponse, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new OAuthException('malformed response from OAuth server token endpoint');
        }

        return $response;
    }
}
