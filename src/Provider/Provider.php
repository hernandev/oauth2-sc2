<?php

namespace SteemConnect\OAuth2\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use SteemConnect\OAuth2\Common\Http\Request;
use SteemConnect\OAuth2\Config\Config;

/**
 * Class Provider.
 *
 * SteemConnect v2 OAuth2 client.
 *
 * This class implements League's OAuth client for SteemConnect authentication on PHP projects.
 */
class Provider extends AbstractProvider
{
    /*
     * Traits: Bearer Authorization.
     */
    use BearerAuthorizationTrait;

    /**
     * @var Config Instance of the configuration class holder.
     */
    protected $config;

    /**
     * @TODO check error messages for correct data key.
     *
     * @var string Erro key to parse error responses.
     */
    protected $responseError = 'error';

    /**
     * @var string Current response code.
     */
    protected $responseCode;

    /**
     * {@inheritdoc}
     */
    public function getDefaultScopes() : array
    {
        return $this->config->getScopes();
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->config->buildUrl('account');
    }

    /**
     * Provider constructor.
     *
     * @param Config $config Provider configuration instance.
     */
    public function __construct(Config $config)
    {
        // assign config on class scope.
        $this->config = $config;

        // call parent constructor to init custom logic.
        parent::__construct($this->parseProviderOptions(), []);
    }

    /**
     * Parses the config object into required provider options.
     *
     * @return array
     */
    protected function parseProviderOptions(): array
    {
        return [
            'redirectUri'  => $this->config->getReturnUrl(),
            'clientId'     => $this->config->getClientId(),
            'clientSecret' => $this->config->getClientSecret(),
        ];
    }

    /**
     * @var string Key used in a token response to identify the resource owner.
     */
    const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'username';

    /**
     * {@inheritdoc}
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->config->buildUrl('authorization');
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->config->buildUrl('access_token');
    }

    /**
     * {@inheritdoc}
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data[$this->responseError])) {
            $error = array_get($data, $this->responseError, null);

            $code = $this->responseCode && !empty(array_get($data, $this->responseCode)) ? array_get($data, $this->responseCode) : 0;

            throw new IdentityProviderException($error, (int) $code, $data);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new ResourceOwner($response);
    }

    /**
     * Parses a return from the authorization flow and returns a access token instance when possible.
     *
     * @param string|null $code
     *
     * @return AccessToken|null
     */
    public function parseReturn(string $code = null): ?AccessToken
    {
        // if no code was passed, request code will be detected, if any.
        $code = $code ? $code : Request::current()->query->get('code', null);

        // just return null for now.
        if (!$code) {
            return null;
        }

        // try a token exchange.
        $accessToken = $this->getAccessToken('authorization_code', ['code' => $code]);

        // returns the token instance, if possible.
        return $accessToken;
    }
}
