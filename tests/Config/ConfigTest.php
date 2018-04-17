<?php

namespace SteemConnect\OAuth2\Config;

use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest.
 */
class ConfigTest extends TestCase
{
    /**
     * @var string Dummy client ID for testing.
     */
    protected $clientId = 'dummy.id';

    /**
     * @var string Dummy client secret for testing.
     */
    protected $clientSecret = 'dummy.secret';

    /**
     * @var string Dummy return URL.
     */
    protected $returnUrl = 'https://return.dummy/callback';

    /**
     * @var array List of default scopes.
     */
    protected $defaultScopes = [
        'login',
        'vote',
        'comment'
    ];

    /**
     * @var Config instance for testing.
     */
    protected $config;

    /**
     * Setup / before tests method.
     */
    public function setUp()
    {
        // call parent setup method.
        parent::setUp();

        // start a dump config instance for testing.
        $this->config = new Config($this->clientId, $this->clientSecret);
    }

    /**
     * Instance ID and Secret tests.
     */
    public function test_instance_id_and_secret()
    {
        // alias the default config instance.
        $config = $this->config;

        // both client and secret must match.
        $this->assertEquals($this->clientId, $config->getClientId());
        $this->assertEquals($this->clientSecret, $config->getClientSecret());
    }

    /**
     * Customize ID and Secret after instance test.
     */
    public function test_override_id_and_secret()
    {
        // alias the default config instance.
        $config = $this->config;

        // override the client id and secret on config instance.
        $config->setClientId('custom.dummy.id');
        $config->setClientSecret('custom.dummy.secret');

        // assert the custom values were correctly set and retrieved.
        $this->assertEquals('custom.dummy.id', $config->getClientId());
        $this->assertEquals('custom.dummy.secret', $config->getClientSecret());
    }

    /**
     * Default scopes testing.
     */
    public function test_default_scopes()
    {
        // alias the config instance.
        $config = $this->config;

        // assert the default scopes were correctly set.
        $this->assertEquals($this->defaultScopes, $config->getScopes());
    }

    /**
     * Custom scopes testing.
     */
    public function test_custom_scopes()
    {
        // alias the config instance.
        $config = $this->config;

        // define custom scopes.
        $scopes = array_merge(['offline', 'comment_delete']);

        // customize the scopes.
        $config->setScopes($scopes);

        // assert the custom values were set.
        $this->assertEquals($scopes, $config->getScopes());
    }

    /**
     * Return / Callback URL testing.
     */
    public function test_return_url()
    {
        // alias the default config instance.
        $config = $this->config;

        // assert no return URL was set to start with.
        $this->assertNull($config->getReturnUrl());

        // set the return null on the config instance.
        $config->setReturnUrl($this->returnUrl);
        // assert the value was correctly set.
        $this->assertEquals($this->returnUrl, $config->getReturnUrl());
    }

    /**
     * Base URL testing.
     */
    public function test_default_base_url()
    {
        // alias config instance.
        $config = $this->config;

        // assert the default config is steemconnect main site.
        $this->assertEquals('https://steemconnect.com', $config->getBaseUrl());
    }

    /**
     * Custom base URL testing.
     */
    public function test_custom_base_url()
    {
        // alias config instance.
        $config = $this->config;

        // declare a custom URL.
        $customUrl = 'http://custom.sc/url';

        // set the custom base url.
        $config->setBaseUrl($customUrl);
        // assert the value was properly set and retrieved.
        $this->assertEquals($customUrl, $config->getBaseUrl());
    }

    /**
     * Test the default endpoint values for all resources.
     */
    public function test_default_endpoints()
    {
        // alias the config instance.
        $config = $this->config;

        // assert the default endpoints match.
        $this->assertEquals('oauth2/authorize', $config->getAuthorizationEndpoint());
        $this->assertEquals('api/oauth2/token', $config->getAccessTokenEndpoint());
        $this->assertEquals('oauth2/token/revoke', $config->getRevokeEndpoint());
        $this->assertEquals('api/me', $config->getAccountEndpoint());
    }

    /**
     * Test the endpoint customization for all resources.
     */
    public function test_custom_endpoints()
    {
        // alias the config instance.
        $config = $this->config;

        // customize the endpoints.
        $config->setAuthorizationEndpoint('custom/authorization');
        $config->setAccessTokenEndpoint('custom/token');
        $config->setRevokeEndpoint('custom/revoke');
        $config->setAccountEndpoint('custom/account');

        // assert the default endpoints match.
        $this->assertEquals('custom/authorization', $config->getAuthorizationEndpoint());
        $this->assertEquals('custom/token', $config->getAccessTokenEndpoint());
        $this->assertEquals('custom/revoke', $config->getRevokeEndpoint());
        $this->assertEquals('custom/account', $config->getAccountEndpoint());
    }

    /**
     * URL builder tests.
     */
    public function test_url_building()
    {
        // alias the config instance.
        $config = $this->config;

        // set a custom base url.
        $config->setBaseUrl('https://custom.url/');

        // set a custom authorization endpoint.
        $config->setAuthorizationEndpoint('/custom/auth/');

        // assert the URL is built against custom values.
        $this->assertEquals('https://custom.url/custom/auth', $config->buildUrl('authorization'));
    }
}