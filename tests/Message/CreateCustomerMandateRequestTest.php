<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\CreateCustomerMandateRequest;
use Omnipay\Mollie\Message\Response\CreateCustomerMandateResponse;
use Omnipay\Tests\TestCase;

class CreateCustomerMandateRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var CreateCustomerMandateRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new CreateCustomerMandateRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(array(
            'apiKey' => "mykey",
            'consumerName' => "Customer A",
            'consumerAccount' => "NL53INGB0000000000",
            "method" => "directdebit",
            'customerReference' => 'cst_bSNBBJBzdG',
            'mandateReference' => "YOUR-COMPANY-MD13804"
        ));
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame("NL53INGB0000000000", $data['consumerAccount']);
        $this->assertSame('directdebit', $data['method']);
        $this->assertSame("YOUR-COMPANY-MD13804", $data['mandateReference']);

        $this->assertCount(4, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CreateCustomerMandateSuccess.txt');

        /** @var CreateCustomerMandateResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("POST", "https://api.mollie.com/v2/customers/cst_bSNBBJBzdG/mandates"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(CreateCustomerMandateResponse::class, $response);
        $this->assertSame("mdt_h3gAaD5zP", $response->getMandateId());

        $this->assertTrue($response->isSuccessful());
        $this->assertJsonStringEqualsJsonString(
            '{"resource":"mandate","id":"mdt_h3gAaD5zP","mode":"test","status":"valid","method":"directdebit","details":{"consumerName":"John Doe","consumerAccount":"NL55INGB0000000000","consumerBic":"INGBNL2A"},"mandateReference":"YOUR-COMPANY-MD13804","signatureDate":"2018-05-07","createdAt":"2018-05-07T10:49:08+00:00","_links":{"self":{"href":"https:\/\/api.mollie.com\/v2\/customers\/cst_4qqhO89gsT\/mandates\/mdt_h3gAaD5zP","type":"application\/hal+json"},"customer":{"href":"https:\/\/api.mollie.com\/v2\/customers\/cst_4qqhO89gsT","type":"application\/hal+json"},"documentation":{"href":"https:\/\/docs.mollie.com\/reference\/v2\/mandates-api\/create-mandate","type":"text\/html"}}}',
            $response->getMessage()
        );
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CreateCustomerMandateFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertJsonStringEqualsJsonString(
            '{"status":401,"title":"Unauthorized Request","detail":"Missing authentication, or failed to authenticate","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/authentication","type":"text\/html"}}}',
            $response->getMessage()
        );
    }
}
