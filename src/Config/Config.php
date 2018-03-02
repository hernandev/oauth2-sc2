<?php

namespace SteemConnect\OAuth2\Config;

use Illuminate\Support\Arr;

/**
 * Class Config.
 *
 * This class holds sensitive and changeable oauth values.
 *
 * SC2 == Steem Connect version 2.
 */
class Config
{
    /**
     * @var string|null OAuth2 client ID from SC2.
     */
    protected $clientId = null;

    /**
     * @var string|null OAuth2 client secret from SC2.
     */
    protected $clientSecret = null;

    /**
     * @var null Callback / return URL. Must match URL configured on SC2 dashboard.
     */
    protected $returnUrl = null;

    /**
     * Note for Developers: For those looking at balance transfers and related operations, the required claim
     * is 'custom_json'.
     *
     * @var array List of default scopes.
     */
    protected $scopes = [
        'login',                 // allows to verify the Steem identity.
        // 'offline',            // allows long-lived tokens.
        'vote',                  // allows upvote, downvote or unvote a post or comment.
        'comment',               // allows publishing or editing a post or a comment.
        // 'comment_delete',     // allows deleting a post or comment.
        // 'comment_options',    // allows adding options for a post or comment.
        // 'custom_json',        // allows following, unfollowing, ignoring, rebloging or any custom_json operations.
        // claim_reward_balance, // allows claiming a reward in behalf of the user.
    ];

    /**
     * @var string SC2 API base URL.
     */
    protected $baseUrl = 'https://v2.steemconnect.com';

    /**
     * @var array List of knows SC2 endpoints.
     */
    protected $endpoints = [
        // OAuth2 endpoints.
        'authorization' => 'oauth2/authorize',
        'access_token'  => 'api/oauth2/token',
        'revoke'        => 'oauth2/token/revoke',
        // SC2 API endpoints.
        'account'       => 'api/me'
    ];

    /**
     * Config constructor.
     *
     * @param string $clientId Client ID is the Steem account username, created under SC2 dashboard.
     * @param string $clientSecret Client Secret can be obtained on the SC2 dashboard.
     */
    public function __construct(string $clientId, string $clientSecret)
    {
        // set client ID.
        $this->clientId = $clientId;

        // set client secret.
        $this->clientSecret = $clientSecret;
    }

    /**
     * Retrieves the client ID (username) registered on SC2 and set on this config class instance.
     *
     * @return null|string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * Configure the SC2 client ID (Steem account created through SC2 dashboard).
     *
     * @param null|string $clientId
     *
     * @return Config
     */
    public function setClientId(string $clientId): Config
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Retrieves the SC2 client secret (which can be retrieved under SC2 dashboard).
     *
     * @return null|string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * Configure the SC2 client secret, used to authenticate the authorization requests.
     *
     * @param null|string $clientSecret
     *
     * @return Config
     */
    public function setClientSecret(string $clientSecret): Config
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Retrieves the URL which the SC2 must redirect to after authorization.
     *
     * @return null|string
     */
    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }

    /**
     * Configure the return/callback return url.
     *
     * @param null|string $returnUrl
     *
     * @return Config
     */
    public function setReturnUrl(string $returnUrl): Config
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    /**
     * Returns the list of configured scopes to request on authorization.
     *
     * @return array
     */
    public function getScopes(): array
    {
        return (array) $this->scopes;
    }

    /**
     * Customize the scopes that will be required from users.
     *
     * @param array $scopes
     *
     * @return Config
     */
    public function setScopes(array $scopes = []) : Config
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * Returns the base URL in which the calls will be made.
     * @return string
     */
    public function getBaseUrl() : string
    {
        return $this->baseUrl;
    }

    /**
     * Returns the base URL in which the calls will be made.
     *
     * @param string $baseUrl Base SC2 URL.
     *
     * @return $this
     */
    public function setBaseUrl(string $baseUrl) : Config
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Retrieves the OAuth2 authorization URL on SC2 relative to backend calls.
     *
     * -- Note for developers:
     * ---- Notice this one does not have the prefix /api since that URL is intended for browser calls only.
     *
     * @return string
     */
    public function getAuthorizationEndpoint(): string
    {
        return $this->getEndpoint('authorization');
    }

    /**
     * Configure a custom SC2 endpoint for authorization.
     *
     * This method should only be used when running this library against a custom SC2 install.
     *
     * @param string $authorizationEndpoint
     *
     * @return Config
     */
    public function setAuthorizationEndpoint(string $authorizationEndpoint): Config
    {
        $this->setEndpoint('authorization', $authorizationEndpoint);

        return $this;
    }

    /**
     * Retrieves the OAuth2 endpoint on SC2 that exchanges access codes for tokens.
     *
     * @return string
     */
    public function getAccessTokenEndpoint(): string
    {
        return $this->getEndpoint('access_token');
    }

    /**
     * Customize the SC2 endpoint used to exchange access codes for tokens.
     *
     * This method should only be used when running this library against a custom SC2 install.
     *
     * @param string $accessTokenEndpoint
     *
     * @return Config
     */
    public function setAccessTokenEndpoint(string $accessTokenEndpoint): Config
    {
        $this->setEndpoint('access_token', $accessTokenEndpoint);

        return $this;
    }

    /**
     * Retrieves the OAuth2 endpoint for revoking tokens.
     *
     * @return string
     */
    public function getRevokeEndpoint(): string
    {
        return $this->getEndpoint('revoke');
    }

    /**
     * Customize the token revoke endpoint.
     *
     * This method should only be used when running this library against a custom SC2 install.
     *
     * @param string $revokeEndpoint
     *
     * @return Config
     */
    public function setRevokeEndpoint(string $revokeEndpoint): Config
    {
        $this->setEndpoint('revoke', $revokeEndpoint);

        return $this;
    }

    /**
     * Retrieves the endpoint used obtain information about the account/user which granted the application permissions.
     *
     * @return string
     */
    public function getAccountEndpoint(): string
    {
        return $this->getEndpoint('account');
    }

    /**
     * Changes the default SC2 account information URL for custom SC2 install.
     *
     * @param string $accountEndpoint
     *
     * @return $this
     */
    public function setAccountEndpoint(string $accountEndpoint): Config
    {
        $this->setEndpoint('account', $accountEndpoint);

        return $this;
    }

    /**
     * Builds a full URL using the endpoint and the base URL.
     *
     * @param string|null $endpoint
     *
     * @return string
     */
    public function buildUrl(string $endpoint = null) : string
    {
        if (Arr::has($this->endpoints, $endpoint)) {
            $endpoint = $this->getEndpoint($endpoint);
        }

        return trim($this->getBaseUrl(), '/') . "/" . trim($endpoint, '/');
    }

    /**
     * Endpoint retriever.
     *
     * @param string $resourceKey Key of the resource being looked for,
     *
     * @return string URI / Endpoint on SC2 API.
     */
    protected function getEndpoint(string $resourceKey) : string
    {
        return array_get($this->endpoints, $resourceKey, '');
    }

    /**
     * Customize a given endpoint.
     *
     * @param string $resourceKey Key of the resource being looked for,
     * @param string $uri Endpoint to customize.
     *
     * @return string URI / Endpoint on SC2 API.
     */
    protected function setEndpoint(string $resourceKey, string $uri) : string
    {
        return !!array_set($this->endpoints, $resourceKey, $uri);
    }
}