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

require_once __DIR__.'/Test/TestRandom.php';
require_once __DIR__.'/Test/TestHttpClient.php';

use fkooman\OAuth\Client\Test\TestRandom;
use fkooman\OAuth\Client\Test\TestHttpClient;
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
        $this->assertNull($accessToken->getScope());
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
        $this->assertNull($accessToken->getScope());
    }

    /**
     * @expectedException \fkooman\OAuth\Client\Exception\OAuthException
     * @expectedExceptionMessage state from authorizationRequestUri does not equal authorizationResponseState
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
