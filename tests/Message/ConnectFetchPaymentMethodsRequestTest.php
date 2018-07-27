<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\PaymentMethod;
use Omnipay\Mollie\Message\Request\ConnectFetchPaymentMethodsRequest;
use Omnipay\Mollie\Message\Response\FetchPaymentMethodsResponse;
use Omnipay\Tests\TestCase;

class ConnectFetchPaymentMethodsRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var ConnectFetchPaymentMethodsRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new ConnectFetchPaymentMethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'mykey',
            'profileId' => 'pfl_3RkSN1zuPE',
            'testmode' => true,
        ));
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertCount(2, $data);

        $this->assertSame('pfl_3RkSN1zuPE', $data['profileId']);
        $this->assertSame(true, $data['testmode']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('ConnectFetchPaymentMethodsSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/methods?profileId=pfl_3RkSN1zuPE&testmode=true"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(FetchPaymentMethodsResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $paymentMethods = $response->getPaymentMethods();
        $this->assertCount(2, $paymentMethods);

        $expectedPaymentMethod = array(
            new PaymentMethod('ideal', 'iDEAL'),
            new PaymentMethod('banktransfer', 'Bank transfer')
        );

        $this->assertEquals($expectedPaymentMethod, $paymentMethods);
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('ConnectFetchPaymentMethodsFailure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/methods?profileId=pfl_3RkSN1zuPE&testmode=true"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(FetchPaymentMethodsResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('{"status":401,"title":"Unauthorized Request","detail":"Missing authentication, or failed to authenticate","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/authentication","type":"text\/html"}}}', $response->getMessage());
        $this->assertEmpty($response->getPaymentMethods());
    }
}
