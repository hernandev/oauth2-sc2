<?php

namespace SteemConnect\OAuth2\Provider;

use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;
use SteemConnect\OAuth2\Config\Config;

class ProviderTest extends TestCase
{
    /**
     * @var Config instance.
     */
    protected $config;

    /**
     * @var Provider instance.
     */
    protected $provider;

    protected $accessToken;

    /**
     * Parent setup call.
     */
    public function setUp()
    {
        // parent setup call.
        parent::setUp();

        // creates a dummy access token instance.
        $this->accessToken = new AccessToken(['access_token' => 'dummy']);

        // create a new config object.
        $this->config = new Config('dummy.id', 'dummy.secret');
        // setup the return point.
        $this->config->setReturnUrl('https://return-to.me/callback');

        // create a new provider instance.
        $this->provider = new Provider($this->config);
    }

    /**
     * Scope testing.
     */
    public function test_scope_parsing_on_provider()
    {
        $this->assertEquals($this->config->getScopes(), $this->provider->getDefaultScopes());
    }

    /**
     * Authorization URL testing.
     */
    public function test_authorization_url_parsing_on_provider()
    {
        $this->assertEquals($this->config->buildUrl('authorization'), $this->provider->getBaseAuthorizationUrl());
    }

    /**
     * Token URL testing.
     */
    public function test_access_token_url_parsing_on_provider()
    {
        $this->assertEquals($this->config->buildUrl('access_token'), $this->provider->getBaseAccessTokenUrl([]));
    }

    /**
     * Resource Owner URL testing.
     */
    public function test_resource_owner_url_parsing()
    {
        $this->assertEquals($this->config->buildUrl('account'), $this->provider->getResourceOwnerDetailsUrl($this->accessToken));
    }
}