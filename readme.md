## SteemConnect V2: OAuth2 Client / Provider

[![Build Status](https://travis-ci.org/hernandev/oauth2-sc2.svg?branch=master)](https://travis-ci.org/hernandev/oauth2-sc2)
[![Codecov](https://codecov.io/gh/hernandev/oauth2-sc2/branch/master/graph/badge.svg)](https://codecov.io/gh/hernandev/oauth2-sc2)
[![Latest Stable Version](https://poser.pugx.org/hernandev/oauth2-sc2/v/stable)](https://packagist.org/packages/hernandev/oauth2-sc2)
[![License](https://poser.pugx.org/hernandev/oauth2-sc2/license)](https://packagist.org/packages/hernandev/oauth2-sc2)

This library is a simple, easy implement OAuth2 client for SteemConnect V2 clients.

It takes the pain from integrating and parsing all the specifics and delivers a great authorization flow for those who aim to handle authorization through SteemConnect V2.

### 0. Why?

Well, OAuth2 is not that complicated, this project started while building a SteemConnect V2 SDK for PHP, so this project is the Authorization part of another package coming on the next days.

### 1. Installation.

All you need to do is install this library as a dependency on your project, through composer:

```bash
composer require hernandev/oauth2-sc2
```

### 2. Usage:

Before using thins library, keep in mind you'll need a SteemConnect application client ID and secret.

#### 2.1. Configuring:

It could not be more simple. Just create a config instance, passing your application credentials:

```php
use SteemConnect\OAuth2\Config\Config;

// creates the configuration object:
$config = new Config('your.app', 'your-long-secret-id-here-keep-it-secret');

```

After setting your credentials, you will need to decide with scopes you will ask permission to:

```php
// set the scopes you want.
$config->setScopes([
    'login', 'vote', 'comment', 'comment_delete'
]);
```

If you are not sure about the scopes you will need, those are documented on the SteemConnect wiki.

Finally, we will configure, to which URL the user should return, after granting you the permissions:

```php
// set the return / callback URL.
$config->setReturnUrl('https://my-steem-app-is-awesome.com/login/return');
```

#### 2.2. Redirecting to Authorization:

That was really, all you need to configure to use the library, pretty cool ham?

Now, of course, you need to redirect the users, to SteemConnect where they will authorize you to act on their behalf.

```php
use SteemConnect\OAuth2\Provider\Provider;

// first, we create a provider instance, passing the configuration needed:
$provider = new Provider($config);
```

// finally, we can get the redirect URL, so we can send users to SteemConnect:

```php
// get the URL string that you will redirect to.
$provider->getAuthorizationUrl()
```

#### 2.3. Parsing the Return:

Guess what? super hard to do:

You will need both the config and provider code used before, so I assume you will be clever and put that logic on a common place.

```php
// just call the parse return URL and this library will automatically exchange the access code by the actual token: 
$token = $provider->parseReturn();
```

On the previous call, $token is an instance of `AccessToken` class, which can be used for the following things:

```php
// gets the actual token that will be used on requests.
$token->getToken();

// gets the expiration date, so you know the token is no longer valid.
$token->getExpires();

// gets the refresh token, which may be used to issue a new token, after the original one expired.
$token->getRefreshToken();

// gets the standard ID for the account athorizing your app, it means, this field retrieves the account @username
$token->getResourceOwnerId();
```

**That's All Folks!**

#### 2.4. Extras:

Of course there are extras!

This library implements the `ResourceOwner` interface, which means you can also query right away some information about the account which granted you permissions:

```php
// gets the resource owner instance.
$owner = $provider->getResourceOwner($token);

// now you can use any key you may see on steemd.com directly on that $owner object!!!

$owner->profile_json;
$owner->balance;
$owner->reputation;
// and so on...
```
