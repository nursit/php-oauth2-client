[![Build Status](https://travis-ci.org/fkooman/php-oauth2-client.svg?branch=master)](https://travis-ci.org/fkooman/php-oauth2-client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fkooman/php-oauth2-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fkooman/php-oauth2-client/?branch=master)

# Introduction
This is a very simple OAuth 2.0 client for integration in 
your own application. It has minimal dependencies, but still tries to be secure. 
The main purpose is to be compatible with PHP 5.4.

**NOTE**: if you are not bound to PHP 5.4, use the OAuth 2.0 client of 
the League of Extraordinary Packages! It can be found 
[here](http://oauth2-client.thephpleague.com/).

# Features

- Simplicity
- Easy integration with your own application and/or framework;
- Does not enforce a framework on you;
- Only "authorization code" profile support, will not implement anything else;
- Only conforming OAuth 2.0 servers will work, this library will not get out of 
  its way to deal with services that blatantly violate the OAuth 2.0 RFC, the 
  exception may be if a fix does not break conforming servers;
- There will be no toggles to shoot yourself in the foot;
- Uses `random_bytes` polyfill on PHP < 7.0 for generating the `state` value

# API

The API is very simple.

## Provider 

To create a `Provider` object you need some information from your OAuth 2.0 
provider.

    $provider = new \fkooman\OAuth\Client\Provider(
        'my_client_id',                  # the client id
        'my_client_secret',              # the client secret
        'https://example.org/authorize', # the authorization endpoint
        'https://example.org/token'      # the token endpoint
    );

# OAuth2Client

To instantiate the OAuth class you need the `Provider` object, see above and
choose a HTTP client implementation that will be used to exchange an 
authorization code for an access token. By default a simple Guzzle HTTP client 
is available.

    $client = new \fkooman\OAuth\Client\OAuth2Client(
        $provider,
        new \fkooman\OAuth\Client\GuzzleHttpClient(new \GuzzleHttp\Client())
    );

To obtain a prepared authorization request URI you can call 
`getAuthorizationRequestUri`:

    $authorizationRequestUri = $client->getAuthorizationRequestUri(
        'requested_scope',                # the requested OAuth scope
        'https://my.example.org/callback' # the redirect URI the OAuth service
                                          # redirects you back to, must usually
                                          # be registered at the OAuth provider
    );

The return value can be used to redirect the browser to the OAuth service 
provider to obtain access. However, you MUST also store this URL for use by the
callback endpoint, e.g. in the user's session.
    
    // store the state
    $_SESSION['oauth2_session'] = $authorizationRequestUri;
    
    // redirect the browser to the authorization endpoint (with a 302)
    http_response_code(302);
    header(sprintf('Location: %s', $authorizationRequestUri));

Your application MUST also listen on the redirect URI specified above, i.e. 
`https://my.example.org/callback` and listen for two query parameters in 
particular, `code` and `state`. These need to be provided to the 
`getAccessToken` method. Typically the OAuth provider will send you back to 
your redirect URI by adding some additional parameters:

    https://my.example.org/callback?code=12345&state=abcde

Now those two values need to be provided to the `getAccessToken` method:

    $accessToken = $client->getAccessToken(
        $_SESSION['oauth2_session'], # URI from session
        $_GET['code'],               # the code value (e.g. 12345)
        $_GET['state']               # the state value (e.g. abcde)
    );

    // unset session field as to not allow additional redirects to the same 
    // URI to attempt to get another access token with this code
    unset($_SESSION['oauth2_session']);
    
    // get the access token value
    echo $accessToken->getToken();
    // get the token type, usually "bearer"
    echo $accessToken->getTokenType();
    // get the time in which the token will expire, null if not provided
    echo $accessToken->getExpiresIn();
    // get the obtained scope, null if not provided
    echo $accessToken->getScope();

Now with this access token you can perform requests at the OAuth service 
provider's API endpoint. Dealing with that is out of scope of this library, 
just as storing the access token for later use.

# Security

As always, make sure you understand what you are doing. If you are using HTTP 
sessions for storing the "state", make sure you follow 
[these](https://paragonie.com/blog/2015/04/fast-track-safe-and-secure-php-sessions) 
best practices!

OAuth 2.0 is very complicated to get right, even if you don't make the obvious 
mistakes, so please make sure you read the RFC and related security documents,
i.e. [RFC 6749](https://tools.ietf.org/html/rfc6749), 
[RFC 6750](https://tools.ietf.org/html/rfc6750) and 
[RFC 6819](https://tools.ietf.org/html/rfc6819).

Make sure you send all relevant "security headers" to the browser as well, see 
e.g. [securityheaders.io](https://securityheaders.io/).
