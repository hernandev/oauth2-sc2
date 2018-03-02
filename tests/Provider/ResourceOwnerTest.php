<?php

namespace SteemConnect\OAuth2\Provider;

use PHPUnit\Framework\TestCase;

/**
 * Class ResourceOwnerTest.
 *
 * Unit tests for the resource owner logic.
 */
class ResourceOwnerTest extends TestCase
{
    /**
     * @var array Some dummy response data to play with.
     */
    protected $data = [
        'account' => [
            'name' => 'John Doe',
            'stats' => [
                'foo' => 'bar',
            ]
        ]
    ];

    /**
     * @var ResourceOwner instance.
     */
    protected $owner;

    /**
     * Setup method.
     */
    public function setUp()
    {
        // call parent.
        parent::setUp();

        $this->owner = new ResourceOwner($this->data);
    }

    /**
     * Resource creation test.
     */
    public function test_resource_creation_key()
    {
        $owner = $this->owner;

        $this->assertEquals($this->data['account'], $owner->toArray());
    }

    /**
     * Custom resource creation test.
     */
    public function test_resource_creation_non_standard()
    {
        $owner = new ResourceOwner($this->data['account']);

        $this->assertEquals($this->data['account'], $owner->toArray());
    }

    /**
     * Magic accessor testing.
     */
    public function test_magic_key_parsing()
    {
        $owner = $this->owner;

        $this->assertEquals($owner->name, $this->data['account']['name']);
    }

    /**
     * Name / ID testing.
     */
    public function test_name_as_id()
    {
        $owner = $this->owner;

        $this->assertEquals($owner->getId(), $this->data['account']['name']);
    }
}