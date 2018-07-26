<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\ConnectUpdateCustomerRequest;
use Omnipay\Mollie\Message\Response\UpdateCustomerResponse;
use Omnipay\Tests\TestCase;

class ConnectUpdateCustomerRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     *
     * @var ConnectUpdateCustomerRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new ConnectUpdateCustomerRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(array(
            'apiKey'            => 'mykey',
            'customerReference' => 'cst_EEbQf6Q4hM',
            'description'       => 'Jane Doe - Mollie Connect',
            'email'             => 'john@doe.com',
            'locale'            => 'nl_NL',
            'metadata'          => 'Just some meta data.',
            'testmode'          => true
        ));
    }

    /**
     * @throws InvalidRequestException
     */
    public function testData()
    {
        $this->request->initialize(array(
            'apiKey'            => 'mykey',
            'customerReference' => 'cst_EEbQf6Q4hM',
            'description'       => 'Jane Doe - Mollie Connect',
            'email'             => 'john@doe.com',
            'metadata'          => 'Just some meta data.',
            'testmode'          => true,
        ));

        $data = $this->request->getData();

        $this->assertSame("Jane Doe - Mollie Connect", $data['name']);
        $this->assertSame('john@doe.com', $data['email']);
        $this->assertSame('Just some meta data.', $data['metadata']);
        $this->assertSame(true, $data['testmode']);
        $this->assertCount(5, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('ConnectUpdateCustomerSuccess.txt');

        /** @var ConnectUpdateCustomerRequest $response */
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "POST",
                "https://api.mollie.com/v2/customers/cst_EEbQf6Q4hM",
                [],
                '{  
                    "name": "Jane Doe - Mollie Connect",
                    "email": "john@doe.com",
                    "metadata": "Just some meta data.",
                    "locale": "nl_NL",
                    "testmode": true
                }'
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(UpdateCustomerResponse::class, $response);
        $this->assertSame('cst_EEbQf6Q4hM', $response->getCustomerReference());

        $this->assertTrue($response->isSuccessful());
        $this->assertJsonStringEqualsJsonString(
            '{"resource":"customer","id":"cst_EEbQf6Q4hM","mode":"test","name":"Jane Roe - Mollie Connect","email":"john@doe.com","locale":null,"metadata":"Just some meta data.","createdAt":"2018-07-26T13:44:48+00:00","_links":{"self":{"href":"https://api.mollie.com/v2/customers/cst_EEbQf6Q4hM","type":"application/hal+json"},"documentation":{"href":"https://docs.mollie.com/reference/v2/customers-api/update-customer","type":"text/html"}}}',
            $response->getMessage()
        );
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('ConnectUpdateCustomerFailure.txt');

        /** @var UpdateCustomerResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("POST", "https://api.mollie.com/v2/customers/cst_EEbQf6Q4hM"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(UpdateCustomerResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getCustomerReference());
        $this->assertSame('{"status":401,"title":"Unauthorized Request","detail":"Missing authentication, or failed to authenticate","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/authentication","type":"text\/html"}}}', $response->getMessage());
    }
}
