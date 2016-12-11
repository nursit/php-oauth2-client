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

interface HttpClientInterface
{
    /**
     * Obtain an access token through a HTTP POST request.
     *
     * @param Provider $provider the OAuth provider information
     * @param array    $postData the HTTP POST body that has to be part of the
     *                           OAuth token request
     *
     * @return array JSON decoded response from HTTP POST request
     *
     * @throws \RuntimeException if there was an error with the request
     */
    public function post(Provider $provider, array $postData);
}
