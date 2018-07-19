<?php

namespace Omnipay\Mollie\Test\Message;

trait AssertRequestTrait
{
    abstract function assertEquals($expected, $actual, $message = null);

    public function assertEqualRequest(\Psr\Http\Message\RequestInterface $expectedRequest, \Psr\Http\Message\RequestInterface $actualRequest)
    {
        $this->assertEquals($expectedRequest->getUri(), $actualRequest->getUri(), "Request Uri should be the same.");
    }
}