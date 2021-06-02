<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\FetchCustomerRequest;
use Omnipay\Mollie\Message\Response\FetchCustomerResponse;
use Omnipay\Tests\TestCase;

class FetchCustomerRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var FetchCustomerRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new FetchCustomerRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'apiKey'            => 'mykey',
                'customerReference' => 'cst_bSNBBJBzdG',
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
        $this->setMockHttpResponse('FetchCustomerSuccess.txt');

        /** @var FetchCustomerResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/customers/cst_bSNBBJBzdG"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(FetchCustomerResponse::class, $response);
        $this->assertSame('cst_bSNBBJBzdG', $response->getCustomerReference());

        $this->assertTrue($response->isSuccessful());
        $this->assertJsonStringEqualsJsonString(
            '{"resource":"customer","id":"cst_bSNBBJBzdG","mode":"test","name":"John Doe","email":"john@doe.com","locale":"nl_NL","metadata":null,"createdAt":"2018-07-19T12:58:47+00:00","_links":{"self":{"href":"https:\/\/api.mollie.com\/v2\/customers\/cst_6HUkmjwzBB","type":"application\/hal+json"},"documentation":{"href":"https:\/\/docs.mollie.com\/reference\/v2\/customers-api\/get-customer","type":"text\/html"}}}',
            $response->getMessage()
        );
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchCustomerFailure.txt');

        /** @var FetchCustomerResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/customers/cst_bSNBBJBzdG"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(FetchCustomerResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getCustomerReference());
        $this->assertSame('{"status":404,"title":"Not Found","detail":"No customer exists with token cst_6HUkmjwzBBa.","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/handling-errors","type":"text\/html"}}}', $response->getMessage());
    }
}
