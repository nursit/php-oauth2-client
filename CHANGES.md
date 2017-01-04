# Changes

## 4.0.0 (2017-01-04)
- remove Guzzle dependency

## 3.0.2 (2016-01-03)
- be less restrictive for `paragonie/random_compat` dependency

## 3.0.1 (2016-12-12)
- change license to AGPLv3+

## 3.0.0 (2016-11-25)
- if token endpoint does not return a scope value, the scope from the request
  is assumed to be granted (according to specification)
- code cleanup
- HTTP clients should now return array instead of JSON string
- restore Guzzle client again
- remove cURL client again, too hard to get right

## 2.0.2 (2016-11-15)
- use PSR-4 now

## 2.0.1 (2016-09-29)
- fix `expires_in` response from token endpoint, add test for it

## 2.0.0 (2016-08-30)
- remove Guzzle client
- add simple cURL client

## 1.0.1 (2016-06-04)
- add API documentation
- improve input validation

## 1.0.0 (2016-05-30)
- initial release
