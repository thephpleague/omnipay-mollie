<?php

namespace Omnipay\Mollie\Test\Message;

use Omnipay\Mollie\Message\CreateCustomerRequest;
use Omnipay\Tests\TestCase;

class CreateCustomerRequestTest extends TestCase
{
    use AssertRequestTrait;

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
            'description' => 'John Doe',
            'email'       => 'john@doe.com',
            'locale'      => 'nl_NL',
            'metadata'    => 'Just some meta data.',
        ));
    }

    public function testData()
    {
        $this->request->initialize(array(
            'apiKey'      => 'mykey',
            'description' => 'John Doe',
            'email'       => 'john@doe.com',
            'metadata'    => 'Just some meta data.',
        ));
        $data = $this->request->getData();

        $this->assertSame("John Doe", $data['name']);
        $this->assertSame('john@doe.com', $data['email']);
        $this->assertSame('Just some meta data.', $data['metadata']);
        $this->assertCount(4, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CreateCustomerSuccess.txt');

        /** @var \Omnipay\Mollie\Message\CreateCustomerResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(
            new \GuzzleHttp\Psr7\Request(
                "GET",
                "https://api.mollie.com/v2/customers",
                [],
                '{  
                   "name":"John Doe",
                   "email":"john@doe.com",
                   "metadata":"Just some meta data.",
                   "locale":"nl_NL"
                }'
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf('Omnipay\Mollie\Message\CreateCustomerResponse', $response);
        $this->assertSame('cst_bSNBBJBzdG', $response->getCustomerReference());

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CreateCustomerFailure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(new \GuzzleHttp\Psr7\Request("GET", "https://api.mollie.com/v2/customers"), $this->getMockClient()->getLastRequest());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('{"status":401,"title":"Unauthorized Request","detail":"Missing authentication, or failed to authenticate","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/authentication","type":"text\/html"}}}', $response->getMessage());
    }
}