<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Mollie\Gateway;
use Omnipay\Mollie\Message\Request\CompleteOrderRequest;
use Omnipay\Mollie\Message\Request\CompletePurchaseRequest;
use Omnipay\Mollie\Message\Response\CompleteOrderResponse;
use Omnipay\Mollie\Message\Response\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;

class AbstractMollieRequestTest extends TestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    public function setUp(): void
    {
        $this->gateway = new Gateway($this->getHttpClient());
    }


    public function testVersionString()
    {
        $request = $this->gateway->fetchIssuers();
        $request->send();

        /** @var \Psr\Http\Message\RequestInterface $httpRequest */
        $httpRequest = $this->getMockedRequests()[0];

        $versionString = 'Omnipay-Mollie/'.Gateway::GATEWAY_VERSION.' PHP/' . phpversion();
        $this->assertEquals($versionString, $httpRequest->getHeaderLine('User-Agent'));
    }

    public function testCustomVersionStrings()
    {
        $this->gateway->initialize([
            'versionStrings' => ['Acme/6.84']
        ]);
        $request = $this->gateway->fetchIssuers();
        $request->send();

        /** @var \Psr\Http\Message\RequestInterface $httpRequest */
        $httpRequest = $this->getMockedRequests()[0];

        $versionString = 'Omnipay-Mollie/'.Gateway::GATEWAY_VERSION.' PHP/' . phpversion() . ' Acme/6.84';
        $this->assertEquals($versionString, $httpRequest->getHeaderLine('User-Agent'));
    }

    public function testAddVersionString()
    {
        $this->gateway->addVersionString('Acme/6.84');
        $request = $this->gateway->fetchIssuers();
        $request->send();

        /** @var \Psr\Http\Message\RequestInterface $httpRequest */
        $httpRequest = $this->getMockedRequests()[0];

        $versionString = 'Omnipay-Mollie/'.Gateway::GATEWAY_VERSION.' PHP/' . phpversion() . ' Acme/6.84';
        $this->assertEquals($versionString, $httpRequest->getHeaderLine('User-Agent'));
    }
}
