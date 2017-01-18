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

use RuntimeException;

class CurlHttpClient implements HttpClientInterface
{
    /** @var resource */
    private $curlChannel;

    public function __construct()
    {
        if (false === $this->curlChannel = curl_init()) {
            throw new RuntimeException('unable to create cURL channel');
        }
    }

    public function __destruct()
    {
        curl_close($this->curlChannel);
    }

    public function post(Provider $provider, array $postData)
    {
        $curlOptions = [
            CURLOPT_URL => $provider->getTokenEndpoint(),
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_USERPWD => sprintf('%s:%s', $provider->getId(), $provider->getSecret()),
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 0,
        ];

        if (strncmp($curlOptions[CURLOPT_URL], 'https://', 8) == 0) {
            $curlOptions[CURLOPT_PROTOCOLS] = CURLPROTO_HTTPS;
        }

        if (false === curl_setopt_array($this->curlChannel, $curlOptions)) {
            throw new RuntimeException('unable to set cURL options');
        }

        if (false === $responseData = curl_exec($this->curlChannel)) {
            $curlError = curl_error($this->curlChannel);
            throw new RuntimeException(sprintf('failure performing the HTTP request: "%s"', $curlError));
        }

        $decodedResponseData = json_decode($responseData, true);
        if (is_null($decodedResponseData) && JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException('unable to decode JSON');
        }

        return $decodedResponseData;
    }
}
