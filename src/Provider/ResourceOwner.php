<?php

namespace SteemConnect\OAuth2\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * Class ResourceOwner.
 *
 * This class makes it possible to have the standards League's resource owner implementation.
 *
 * However, the accounts are better handled by SteemConnect specific code.
 */
class ResourceOwner implements ResourceOwnerInterface
{
    /**
     * @var array Holds account information.
     */
    protected $accountData = [];

    /**
     * ResourceOwner constructor.
     *
     * @param array $accountData Account information to parse / hold.
     */
    public function __construct(array $accountData = [])
    {
        $this->accountData = array_get($accountData, 'account', $accountData);
    }

    /**
     * On SteemConnect's case, the ID of a resource owner is it's username itself
     * (without the @ sign).
     *
     * @return string
     */
    public function getId()
    {
        return array_get($this->accountData, 'name');
    }

    /**
     * Array representation of the resource owner.
     *
     * @return array Quasi-non-parsed steem account data.
     */
    public function toArray()
    {
        return $this->accountData;
    }

    /**
     * Magic getter for querying values on the account itself.
     *
     * @param string $attribute Account attribute name to query.
     *
     * @return mixed
     */
    public function __get(string $attribute)
    {
        return array_get($this->accountData, $attribute, null);
    }
}
