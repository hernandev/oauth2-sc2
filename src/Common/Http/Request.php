<?php

namespace SteemConnect\OAuth2\Common\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class Request.
 *
 * Implements a singleton around symfony request which is created from global variables.
 */
class Request
{
    /**
     * @var Request|null Current request instance.
     */
    protected static $instance = null;

    /**
     * Protected CurrentRequest constructor.
     */
    protected function __construct()
    {
        //
    }

    /**
     * Current request instance retriever methods.
     *
     * @return SymfonyRequest
     */
    public static function current()
    {
        // this is a dummy call.
        $dummy = new self();

        // assign a new instance only if none is present.
        if (!self::$instance) {
            self::$instance = SymfonyRequest::createFromGlobals();
        }

        // return the instance value.
        return self::$instance;
    }
}