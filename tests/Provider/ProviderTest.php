<?php

namespace SteemConnect\OAuth2\Provider;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;
use SteemConnect\OAuth2\Config\Config;
use Mockery;

/**
 * Class ProviderTest.
 *
 * Unit tests for the provider method.
 */
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

    /**
     * @var AccessToken instance.
     */
    protected $accessToken;

    /**
     * @var array Data for the AccessToken instance.
     */
    protected $accessTokenData = [
        'access_token' => 'mock-access-token',
        'scopes' => ['mock-scopes'],
        'expires_in' => 3600,
        'username' => 'dummy-user',
        'refresh_token' => 'mock-refresh-token',
        'token_type' => 'bearer',
    ];

    /**
     * @var array Dummy account data.
     */
    protected $accountData = [
        'account' => [
            'name' => 'dummy-name',
            'foo' => 'bar'
        ]
    ];

    /**
     * Parent setup call.
     */
    public function setUp()
    {
        // parent setup call.
        parent::setUp();

        // creates a dummy access token instance.
        $this->accessToken = new AccessToken($this->accessTokenData);

        // create a new config object.
        $this->config = new Config('hernandev.app', '4c90e2e77840b97ac001b37236be966cf73ce1373f4b4b5a');
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
        // stub scopes list.
        $scopes = ['login', 'comment'];

        // get a config mock.
        $config = $this->mockConfig();
        // declare a getScopes expectation.
        $config->shouldReceive('getScopes')->andReturn($scopes);

        // start a provider with the mock config.
        $provider = new Provider($config);

        // assert the
        $this->assertEquals($scopes, $provider->getDefaultScopes());
    }

    /**
     * Authorization URL testing.
     */
    public function test_authorization_url_parsing_on_provider()
    {
        // alias the config instance.
        $config = $this->config;

        // assert the provider uses the config values.
        $this->assertEquals($config->buildUrl('authorization'), $this->provider->getBaseAuthorizationUrl());
    }

    /**
     * Token URL testing.
     */
    public function test_access_token_url_parsing_on_provider()
    {
        // alias the config instance.
        $config = $this->config;

        // assert the provider uses the config values.
        $this->assertEquals($config->buildUrl('access_token'), $this->provider->getBaseAccessTokenUrl([]));
    }

    /**
     * Resource Owner URL testing.
     */
    public function test_resource_owner_url_parsing()
    {
        // alias the config instance.
        $config = $this->config;

        // assert the provider uses the config values.
        $this->assertEquals($config->buildUrl('account'), $this->provider->getResourceOwnerDetailsUrl($this->accessToken));
    }

    /**
     * Test for return parsing.
     */
    public function test_code_parsing_with_missing_code()
    {
        // assert no token is returned from a null access code.
        $this->assertNull($this->provider->parseReturn());
    }

    /**
     * Test for code parsing.
     */
    public function test_code_parsing()
    {
        // create a mock http client.
        /** @var  $client */
        $client = $this->getHttpMock($this->accessTokenData);

        // set the mock client on the provider.
        $this->provider->setHttpClient($client);

        // try the code parsing method.
        $token = $this->provider->parseReturn('mock-access-code');

        // assert the token contains the same values.
        $this->assertEquals((string) $this->accessToken, (string) $token);
    }

    /**
     * Test for refresh tokens.
     */
    public function test_refresh_token()
    {
        // create a mock http client.
        /** @var  $client */
        $client = $this->getHttpMock($this->accessTokenData);

        // set the mock client on the provider.
        $this->provider->setHttpClient($client);

        // try the code parsing method.
        $token = $this->provider->refreshTokenString('mock-access-code');

        // assert the token contains the same values.
        $this->assertEquals((string) $token, (string) $this->accessToken);
    }

    /**
     * Tests for refresh token (AccessToken instance).
     */
    public function test_refresh_token_instance()
    {
        // create a mock http client.
        /** @var  $client */
        $client = $this->getHttpMock($this->accessTokenData);

        // set the mock client on the provider.
        $this->provider->setHttpClient($client);

        // try the code parsing method.
        $token = $this->provider->refreshToken(new AccessToken($this->accessTokenData));

        // assert the token contains the same values.
        $this->assertEquals((string) $token, (string) $this->accessToken);

        // copy the token data.
        $noRefreshData = $this->accessTokenData;
        // remove the refresh token from the data.
        unset($noRefreshData['refresh_token']);

        // try the code parsing method.
        $token = $this->provider->refreshToken(new AccessToken($noRefreshData));

        // assert nothing was returned when the refresh is token missing.
        $this->assertNull($token);
    }

    /**
     * Test for code parsing error.
     */
    public function test_code_parsing_error()
    {
        // create a mock http client.
        /** @var  $client */
        $client = $this->getHttpMock(['error' => 'invalid-access-code']);

        // set the mock client on the provider.
        $this->provider->setHttpClient($client);

        // try the code parsing method.
        try {
            // try to parse with error.
            $this->provider->parseReturn('mock-access-code');
        } catch (\Exception $e) {
            // assert the exceptions instance matches.
            $this->assertInstanceOf(IdentityProviderException::class, $e);
        }
    }

    /**
     * Test resource owner parsing.
     */
    public function test_resource_owner_parsing()
    {
        // creates a custom http client for returning account data.
        /** @var  $client */
        $client = $this->getHttpMock($this->accountData);

        // setup the client as the provider http client.
        $this->provider->setHttpClient($client);

        // get the result owner, that will use the mock response.
        $resourceOwner = $this->provider->getResourceOwner($this->accessToken);

        // asset the Id is set as name on the dummy data.
        $this->assertEquals($resourceOwner->getId(), 'dummy-name');
        // asset the magic getter on the response as well.
        $this->assertEquals($resourceOwner->name, 'dummy-name');
    }

    /**
     * Create a simple http client mock for testing http responses.
     *
     * @param array $data
     *
     * @return \Mockery\MockInterface|\GuzzleHttp\ClientInterface
     */
    protected function getHttpMock(array $data = [])
    {
        // create a response mock.
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');

        // create a dummy token response.
        $response->shouldReceive('getBody')->andReturn(json_encode($data));
        // includes the response type / header.
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);

        // mock http client.
        $client = Mockery::mock('GuzzleHttp\ClientInterface');

        // set the response on the mock client.
        $client->shouldReceive('send')->times(1)->andReturn($response);

        // return the client.
        return $client;
    }

    /**
     * Generate a config mock.
     *
     * @return Mockery\MockInterface|Config
     */
    protected function mockConfig()
    {
        // start a config mock.
        $config = Mockery::mock(Config::class);

        // manage default expectations.
        $config->shouldReceive('getClientId')->andReturn('some.client');
        $config->shouldReceive('getClientSecret')->andReturn('some.secret');
        $config->shouldReceive('getReturnUrl')->andReturn('http://some.return/url');

        // return the mock itself.
        return $config;
    }
}