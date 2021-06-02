<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\FetchCustomerMandatesRequest;
use Omnipay\Mollie\Message\Response\FetchCustomerMandatesResponse;
use Omnipay\Tests\TestCase;

class FetchCustomerMandatesRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var FetchCustomerMandatesRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new FetchCustomerMandatesRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'apiKey'            => 'mykey',
                'customerReference' => 'cst_R6JLAuqEgm',
            )
        );
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertEmpty($data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse("FetchCustomerMandatesSuccess.txt");

        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/customers/cst_R6JLAuqEgm/mandates"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(FetchCustomerMandatesResponse::class, $response);

        $mandates = $response->getMandates();

        $this->assertSame("mdt_AcQl5fdL4h", $mandates[0]['id']);
        $this->assertSame("directdebit", $mandates[0]['method']);

        $this->assertTrue($response->hasValidMandates());
        $this->assertJsonStringEqualsJsonString(
            '{"count":5,"_embedded":{"mandates":[{"resource":"mandate","id":"mdt_AcQl5fdL4h","mode":"test","status":"valid","method":"directdebit","details":{"consumerName":"John Doe","consumerAccount":"NL55INGB0000000000","consumerBic":"INGBNL2A"},"mandateReference":null,"signatureDate":"2018-05-07","createdAt":"2018-05-07T10:49:08+00:00","_links":{"self":{"href":"https:\/\/api.mollie.com\/v2\/customers\/cst_8wmqcHMN4U\/mandates\/mdt_AcQl5fdL4h","type":"application\/hal+json"},"customer":{"href":"https:\/\/api.mollie.com\/v2\/customers\/cst_8wmqcHMN4U","type":"application\/hal+json"},"documentation":{"href":"https:\/\/mollie.com\/en\/docs\/reference\/customers\/create-mandate","type":"text\/html"}}},[],[],[],[]]},"_links":{"self":{"href":"https:\/\/api.mollie.com\/v2\/customers\/cst_8wmqcHMN4U\/mandates?limit=5","type":"application\/hal+json"},"previous":null,"next":{"href":"https:\/\/api.mollie.com\/v2\/customers\/cst_8wmqcHMN4U\/mandates?from=mdt_AcQl5fdL4h&limit=5","type":"application\/hal+json"},"documentation":{"href":"https:\/\/docs.mollie.com\/reference\/v2\/mandates-api\/revoke-mandate","type":"text\/html"}}}',
            $response->getMessage()
        );
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchCustomerMandatesFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertJsonStringEqualsJsonString(
            '{"status":404,"title":"Not Found","detail":"No customer exists with token cst_6HUkmjwzBBa.","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/handling-errors","type":"text\/html"}}}',
            $response->getMessage()
        );
    }
}
