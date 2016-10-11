<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Tests\TestCase;

class CreateCustomerRequestTest extends TestCase
{
    /**
     *
     * @var \Omnipay\Mollie\Message\CreateCustomerRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new CreateCustomerRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(array(
            'apiKey'      => 'mykey',
            'description' => 'Test Customer',
            'email'       => 'test123@example.com',
            'locale'      => 'nl_NL',
            'metadata'    => 'Just some meta data.',
        ));
    }

    public function testEndpoint()
    {
        $this->assertSame('https://api.mollie.nl/v1/customers', $this->request->getEndpoint());
    }

    public function testData()
    {
        $this->request->initialize(array(
            'apiKey'      => 'mykey',
            'description' => 'Test Customer',
            'email'       => 'test123@example.com',
            'metadata'    => 'Just some meta data.',
        ));
        $data = $this->request->getData();

        $this->assertSame("Test Customer", $data['name']);
        $this->assertSame('test123@example.com', $data['email']);
        $this->assertSame('Just some meta data.', $data['metadata']);
        $this->assertCount(4, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CreateCustomerSuccess.txt');

        /** @var \Omnipay\Mollie\Message\CreateCustomerResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\CreateCustomerResponse', $response);
        $this->assertSame('cst_bSNBBJBzdG', $response->getCustomerReference());

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CreateCustomerFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Unauthorized request', $response->getMessage());
    }
}