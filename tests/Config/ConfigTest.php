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
    protected $returnUrl = 'https://return.dummy.callback';

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
        parent::setUp();

        $this->config = new Config($this->clientId, $this->clientSecret);
    }

    /**
     * Instance ID and Secret tests.
     */
    public function test_instance_id_and_secret()
    {
        $config = $this->config;

        $this->assertEquals($this->clientId, $config->getClientId());
        $this->assertEquals($this->clientSecret, $config->getClientSecret());
    }

    /**
     * Customize ID and Secret after instance test.
     */
    public function test_override_id_and_secret()
    {
        $config = $this->config;

        $config->setClientId('custom.dummy.id');

        $this->assertEquals('custom.dummy.id', $config->getClientId());

        $config->setClientSecret('custom.dummy.secret');

        $this->assertEquals('custom.dummy.secret', $config->getClientSecret());
    }

    /**
     * Default scopes testing.
     */
    public function test_default_scopes()
    {
        $config = $this->config;

        $this->assertEquals($this->defaultScopes, $config->getScopes());
    }

    /**
     * Custom scopes testing.
     */
    public function test_custom_scopes()
    {
        $config = $this->config;

        $scopes = array_merge($this->defaultScopes, ['offline']);

        $config->setScopes($scopes);

        $this->assertEquals($scopes, $config->getScopes());
    }

    /**
     * Return / Callback URL testing.
     */
    public function test_return_url()
    {
        $config = $this->config;

        $config->setReturnUrl($this->returnUrl);

        $this->assertEquals($this->returnUrl, $config->getReturnUrl());
    }

    /**
     * Base URL testing.
     */
    public function test_default_base_url()
    {
        $config = $this->config;

        $this->assertEquals('https://v2.steemconnect.com', $config->getBaseUrl());
    }

    /**
     * Custom base URL testing.
     */
    public function test_custom_base_url()
    {
        $config = $this->config;

        $config->setBaseUrl('https://custom.steem.connect');

        $this->assertEquals('https://custom.steem.connect', $config->getBaseUrl());
    }

    /**
     * Authorization URL testing.
     */
    public function test_default_and_custom_authorization_endpoints()
    {
        $config =  $this->config;

        $this->assertEquals('oauth2/authorize', $config->getAuthorizationEndpoint());

        $config->setAuthorizationEndpoint('authorize.custom');

        $this->assertEquals('authorize.custom', $config->getAuthorizationEndpoint());
    }

    /**
     * Access Token URL testing.
     */
    public function test_default_and_custom_token_endpoints()
    {
        $config =  $this->config;

        $this->assertEquals('api/oauth2/token', $config->getAccessTokenEndpoint());

        $config->setAccessTokenEndpoint('token.custom');

        $this->assertEquals('token.custom', $config->getAccessTokenEndpoint());
    }

    /**
     * Revoke URL testing.
     */
    public function test_default_and_custom_revoke_endpoints()
    {
        $config =  $this->config;

        $this->assertEquals('oauth2/token/revoke', $config->getRevokeEndpoint());

        $config->setRevokeEndpoint('revoke.custom');

        $this->assertEquals('revoke.custom', $config->getRevokeEndpoint());
    }

    /**
     * Account endpoint testing.
     */
    public function test_default_and_custom_account_endpoints()
    {
        $config =  $this->config;

        $this->assertEquals('api/me', $config->getAccountEndpoint());

        $config->setAccountEndpoint('account.custom');

        $this->assertEquals('account.custom', $config->getAccountEndpoint());
    }

    /**
     * URL builder tests.
     */
    public function test_url_building()
    {
        $config =  $this->config;

        $config->setBaseUrl('https://custom.url/');

        $config->setAuthorizationEndpoint('/custom/auth/');

        $this->assertEquals('https://custom.url/custom/auth', $config->buildUrl('authorization'));
    }
}