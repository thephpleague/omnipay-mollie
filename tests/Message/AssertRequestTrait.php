<?php

namespace Omnipay\Mollie\Test\Message;

trait AssertRequestTrait
{
    abstract function assertEquals($expected, $actual, $message = '', $delta = 0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false);

    abstract function assertJsonStringEqualsJsonString($expected, $actual, $message = null);

    public function assertEqualRequest(\Psr\Http\Message\RequestInterface $expectedRequest, \Psr\Http\Message\RequestInterface $actualRequest)
    {
        $this->assertEquals($expectedRequest->getMethod(), $actualRequest->getMethod(), "Expected request Method should be equal to actual request method.");

        $this->assertEquals($expectedRequest->getUri(), $actualRequest->getUri(), "Expected request Uri should be equal to actual request body.");

        if(!empty((string) $expectedRequest->getBody())) {
            $this->assertJsonStringEqualsJsonString((string) $expectedRequest->getBody(), (string) $actualRequest->getBody(), "Expected request Body should be equal to actual request body.");
        }
    }
}