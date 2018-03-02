<?php

namespace SteemConnect\OAuth2\Common\Http;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class RequestTest.
 *
 * Tests against the HTTP method.
 */
class RequestTest extends TestCase
{
    /**
     * Test protected constructor.
     *
     * @covers \SteemConnect\OAuth2\Common\Http\Request::__construct
     *
     * @throws \ReflectionException
     */
    public function test_request_constructor_is_private()
    {
        // get the reflection instance for request class.
        $reflectionRequest = new \ReflectionClass(Request::class);

        // get the constructor method reflection.
        $constructor = $reflectionRequest->getConstructor();

        // asset the constructor as protected method.
        $this->assertTrue($constructor->isProtected());
    }

    /**
     * Test the instances. to ensure singleton.
     */
    public function test_request_instance_is_always_the_same()
    {
        // get two current request instances from request static method.
        $instanceOne = Request::current();
        $instanceTwo = Request::current();

        // instances one and two should be the same.
        $this->assertSame($instanceOne, $instanceTwo);

        // clone a instance to verify  the same.
        $instanceThree = clone $instanceTwo;

        // instance two and three should be different.
        $this->assertNotSame($instanceTwo, $instanceThree);
    }

    /**
     * Test the correct symfony instance on output.
     */
    public function test_correct_output()
    {
        // get current request.
        $currentRequest = Request::current();

        // asset the output request is an instance of symfony request.
        $this->assertInstanceOf(SymfonyRequest::class, $currentRequest);
    }
}