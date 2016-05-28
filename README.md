[![Build Status](https://travis-ci.org/fkooman/php-oauth2-client.svg?branch=master)](https://travis-ci.org/fkooman/php-oauth2-client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fkooman/php-oauth2-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fkooman/php-oauth2-client/?branch=master)

# Introduction
This is a very simple OAuth 2.0 client for integration in 
your own application. It has minimal depedencies, but still tries to be secure. 
The main purpose is to be compatible with PHP 5.4.

**NOTE**: if you are not bound to PHP 5.4, use the OAuth 2.0 client of 
the League of Extraordinary Packages! It can be found 
[here](http://oauth2-client.thephpleague.com/).

# API

The API is very simple.

## ClientInfo 

To create a `ClientInfo` object you need some information from your OAuth 2.0 
provider.

    $clientInfo = new ClientInfo(
        'my_client_id',                 # the client id
        'my_client_secret',             # the client secret
        'http://example.org/authorize', # the authorization endpoint
        'http://example.org/token'      # the token endpoint
    );

# OAuth2Client

To instantiate the OAuth class you need the `ClientInfo` object, see above and
choose a HTTP client implementation that will be used to exchange an 
authorization code for an access token. By default a Guzzle client is 
available.

    $client = new OAuth2Client(
        $clientInfo,
        new GuzzleHttpClient()
    );

To obtain a prepared authorization request URI you can call 
`getAuthorizationRequestUri`:

    $authorizationRequestUri = $client->getAuthorizationRequestUri(
        'requested_scope',               # the requested OAuth scope
        'http://my.example.org/callback' # the redirect URI the OAuth service
                                         # redirects you back to, must usually
                                         # be registered at the OAuth provider
    );

The return value can be used to redirect the browser to the OAuth service 
provider to obtain access. However, you MUST also store this URL in the user's
session data for later use.

    $_SESSION['oauth_authorization_request_uri'] = $authorizationRequestUri;
    header(sprintf('Location: %s', $authorizationRequestUri));

Your application MUST also listen on the redirect URI specified above and 
listen for two query parameters in particular, `code` and `state`. These need
to be provided to the `getAccessToken` method. Typically the OAuth provider 
will send you back to your redirect URI by adding some additional parameters:

    http://my.example.org/callback?code=12345&state=abcde

Now those two values need to be provided to the `getAccessToken` method:

    $accessToken = $client->getAccessToken(
        $_SESSION['oauth_authorization_request_uri'], # URI from session
        '12345',                                      # the code value
        'abcde'                                       # the state value
    );

Now with this access token you can perform requests at the OAuth service 
provider's API endpoint. Dealing with that is out of scope of this library, 
just as storing the access token for later use.
