<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Tests\TestCase;

class FetchMethodsRequestTest extends TestCase
{
    /**
     * @var \Omnipay\Mollie\Message\FetchMethodsRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new FetchMethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'mykey'
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertEmpty($data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchMethodsSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchMethodsResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame(array(array(
            'id'     => 'ideal',
            'name'   => 'iDEAL',
        )), $response->getMethods());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchMethodsFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchMethodsResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Unauthorized request', $response->getMessage());
        $this->assertNull($response->getIssuers());
    }
}
