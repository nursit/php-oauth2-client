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

namespace fkooman\OAuth\Client\Test;

use fkooman\OAuth\Client\HttpClientInterface;
use fkooman\OAuth\Client\Provider;
use RuntimeException;

class TestHttpClient implements HttpClientInterface
{
    public function post(Provider $provider, array $postData)
    {
        if ('code12345' === $postData['code']) {
            return [
                'access_token' => sprintf(
                    '%s:%s:%s:%s',
                    $provider->getId(),
                    $provider->getSecret(),
                    $provider->getAuthorizationEndpoint(),
                    $provider->getTokenEndpoint()
                ),
                'token_type' => 'bearer',
            ];
        }

        if ('code12345expires' === $postData['code']) {
            return [
                'access_token' => sprintf(
                    '%s:%s:%s:%s',
                    $provider->getId(),
                    $provider->getSecret(),
                    $provider->getAuthorizationEndpoint(),
                    $provider->getTokenEndpoint()
                ),
                'token_type' => 'bearer',
                'expires_in' => 1234567,
            ];
        }

        throw new RuntimeException('invalid code in unit test');
    }
}
