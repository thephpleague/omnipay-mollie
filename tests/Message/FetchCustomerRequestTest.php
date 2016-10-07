<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Tests\TestCase;

class FetchCustomerRequestTest extends TestCase
{
    /**
     * @var \Omnipay\Mollie\Message\FetchCustomerRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new FetchCustomerRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'apiKey'            => 'mykey',
                'customerReference' => 'cst_bSNBBJBzdG',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertCount(0, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchCustomerSuccess.txt');

        /** @var \Omnipay\Mollie\Message\FetchCustomerResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchCustomerResponse', $response);
        $this->assertSame('cst_bSNBBJBzdG', $response->getCustomerReference());

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchCustomerFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getCustomerReference());
        $this->assertSame("The customer id is invalid", $response->getMessage());
    }
}
