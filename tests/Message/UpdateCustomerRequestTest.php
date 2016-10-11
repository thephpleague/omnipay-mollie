<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Tests\TestCase;

class UpdateCustomerRequestTest extends TestCase
{
    /**
     *
     * @var \Omnipay\Mollie\Message\UpdateCustomerRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new UpdateCustomerRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(array(
            'apiKey'            => 'mykey',
            'customerReference' => 'cst_bSNBBJBzdG',
            'description'       => 'Test Customer2',
            'email'             => 'test123@example.com',
            'locale'            => 'nl_NL',
            'metadata'          => 'Just some meta data.',
        ));
    }

    public function testEndpoint()
    {
        $this->assertSame(
            'https://api.mollie.nl/v1/customers/'.$this->request->getCustomerReference(),
            $this->request->getEndpoint()
        );
    }

    public function testData()
    {
        $this->request->initialize(array(
            'apiKey'            => 'mykey',
            'customerReference' => 'cst_bSNBBJBzdG',
            'description'       => 'Test Customer2',
            'email'             => 'test123@example.com',
            'metadata'          => 'Just some meta data.',
        ));

        $data = $this->request->getData();

        $this->assertSame("cst_bSNBBJBzdG", $data['id']);
        $this->assertSame("Test Customer2", $data['name']);
        $this->assertSame('test123@example.com', $data['email']);
        $this->assertSame('Just some meta data.', $data['metadata']);
        $this->assertCount(5, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('UpdateCustomerSuccess.txt');

        /** @var \Omnipay\Mollie\Message\UpdateCustomerResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\UpdateCustomerResponse', $response);
        $this->assertSame('cst_bSNBBJBzdG', $response->getCustomerReference());

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('UpdateCustomerFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getCustomerReference());
        $this->assertSame('Unauthorized request', $response->getMessage());
    }
}
