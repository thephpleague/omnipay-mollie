<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\PaymentMethod;
use Omnipay\Mollie\Message\Request\FetchPaymentMethodsRequest;
use Omnipay\Mollie\Message\Response\FetchPaymentMethodsResponse;
use Omnipay\Tests\TestCase;

class FetchPaymentMethodsRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var FetchPaymentMethodsRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new FetchPaymentMethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'mykey'
        ));
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
        $this->setMockHttpResponse('FetchPaymentMethodsSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/methods"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(FetchPaymentMethodsResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $paymentMethods = $response->getPaymentMethods();
        $this->assertCount(12, $paymentMethods);

        $expectedPaymentMethod = new PaymentMethod('ideal', 'iDEAL');

        $this->assertEquals($expectedPaymentMethod, $paymentMethods[0]);
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchPaymentMethodsFailure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/methods"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(FetchPaymentMethodsResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('{"status":401,"title":"Unauthorized Request","detail":"Missing authentication, or failed to authenticate","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/authentication","type":"text\/html"}}}', $response->getMessage());
        $this->assertEmpty($response->getPaymentMethods());
    }
}
