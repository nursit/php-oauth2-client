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

require_once __DIR__.'/Test/TestRandom.php';
require_once __DIR__.'/Test/TestHttpClient.php';

use fkooman\OAuth\Client\Test\TestHttpClient;
use fkooman\OAuth\Client\Test\TestRandom;
use PHPUnit_Framework_TestCase;

class OAuth2ClientTest extends PHPUnit_Framework_TestCase
{
    public function testGetAuthorizationRequestUri()
    {
        $o = new OAuth2Client(
            new Provider('foo', 'bar', 'http://localhost/authorize', 'http://localhost/token'),
            new TestHttpClient(),
            new TestRandom()
        );
        $this->assertSame(
            'http://localhost/authorize?client_id=foo&redirect_uri=http%3A%2F%2Fexample.org%2Fcallback&scope=my_scope&state=state12345abcde&response_type=code',
            $o->getAuthorizationRequestUri('my_scope', 'http://example.org/callback')
        );
    }

    public function testGetAccessToken()
    {
        $o = new OAuth2Client(
            new Provider('foo', 'bar', 'http://localhost/authorize', 'http://localhost/token'),
            new TestHttpClient(),
            new TestRandom()
        );

        $authorizationRequestUri = 'http://localhost/authorize?client_id=foo&redirect_uri=http%3A%2F%2Fexample.org%2Fcallback&scope=my_scope&state=state12345abcde&response_type=code';

        $accessToken = $o->getAccessToken($authorizationRequestUri, 'code12345', 'state12345abcde');
        $this->assertSame('foo:bar:http://localhost/authorize:http://localhost/token', $accessToken->getToken());
        $this->assertSame('bearer', $accessToken->getTokenType());
        $this->assertNull($accessToken->getExpiresIn());
        $this->assertSame('my_scope', $accessToken->getScope());
    }

    public function testGetAccessTokenWithExpires()
    {
        $o = new OAuth2Client(
            new Provider('foo', 'bar', 'http://localhost/authorize', 'http://localhost/token'),
            new TestHttpClient(),
            new TestRandom()
        );

        $authorizationRequestUri = 'http://localhost/authorize?client_id=foo&redirect_uri=http%3A%2F%2Fexample.org%2Fcallback&scope=my_scope&state=state12345abcde&response_type=code';

        $accessToken = $o->getAccessToken($authorizationRequestUri, 'code12345expires', 'state12345abcde');
        $this->assertSame('foo:bar:http://localhost/authorize:http://localhost/token', $accessToken->getToken());
        $this->assertSame('bearer', $accessToken->getTokenType());
        $this->assertSame(1234567, $accessToken->getExpiresIn());
        $this->assertSame('my_scope', $accessToken->getScope());
    }

    /**
     * @expectedException \fkooman\OAuth\Client\Exception\OAuthException
     * @expectedExceptionMessage invalid OAuth state
     */
    public function testGetAccessTokenNonMatchingState()
    {
        $o = new OAuth2Client(
            new Provider('foo', 'bar', 'http://localhost/authorize', 'http://localhost/token'),
            new TestHttpClient(),
            new TestRandom()
        );

        $authorizationRequestUri = 'http://localhost/authorize?client_id=foo&redirect_uri=http%3A%2F%2Fexample.org%2Fcallback&scope=my_scope&state=brokenstate&response_type=code';
        $o->getAccessToken($authorizationRequestUri, 'code12345', 'state12345abcde');
    }
}
