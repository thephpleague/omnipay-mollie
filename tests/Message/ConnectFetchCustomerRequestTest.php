<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\ConnectFetchCustomerRequest;
use Omnipay\Mollie\Message\Response\FetchCustomerResponse;
use Omnipay\Tests\TestCase;

class ConnectFetchCustomerRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var ConnectFetchCustomerRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new ConnectFetchCustomerRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'apiKey'            => 'mykey',
                'customerReference' => 'cst_EEbQf6Q4hM',
            )
        );
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertCount(0, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('ConnectFetchCustomerSuccess.txt');

        /** @var ConnectFetchCustomerRequest $response */
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/customers/cst_EEbQf6Q4hM?testmode=true"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(FetchCustomerResponse::class, $response);
        $this->assertSame('cst_EEbQf6Q4hM', $response->getCustomerReference());

        $this->assertTrue($response->isSuccessful());
        $this->assertJsonStringEqualsJsonString(
            '{"resource":"customer","id":"cst_EEbQf6Q4hM","mode":"test","name":"Jane Roe - Mollie Connect","email":"john@doe.com","locale":null,"metadata":"Just some meta data.","createdAt":"2018-07-26T13:44:48+00:00","_links":{"self":{"href":"https://api.mollie.com/v2/customers/cst_EEbQf6Q4hM?testmode=true","type":"application/hal+json"},"documentation":{"href":"https://docs.mollie.com/reference/v2/customers-api/get-customer","type":"text/html"}}}',
            $response->getMessage()
        );
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('ConnectFetchCustomerFailure.txt');

        /** @var ConnectFetchCustomerRequest $response */
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/customers/cst_EEbQf6Q4hM"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(FetchCustomerResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getCustomerReference());
        $this->assertSame('{"status":404,"title":"Not Found","detail":"No customer exists with token cst_EEbQf6Q4hM.","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/handling-errors","type":"text\/html"}}}', $response->getMessage());
    }
}
