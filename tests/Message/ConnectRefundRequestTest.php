<?php
namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Mollie\Message\Request\ConnectPurchaseRequest;
use Omnipay\Mollie\Message\Request\ConnectRefundRequest;
use Omnipay\Mollie\Message\Response\RefundResponse;
use Omnipay\Tests\TestCase;

class ConnectRefundRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var ConnectRefundRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new ConnectRefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'apiKey' => 'mykey',
            'transactionReference' => 'tr_98nUH7v5bT',
            'amount' => '10.00',
            'currency' => 'EUR',
            'testmode' => true,
        ]);
    }

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testGetData()
    {
        $this->request->initialize([
            'apiKey' => 'mykey',
            'amount' => '10.00',
            'currency' => 'EUR',
            'transactionReference' => 'tr_98nUH7v5bT',
            'testmode' => true,
        ]);

        $data = $this->request->getData();

        $this->assertSame(["value" => "10.00", "currency" => "EUR"], $data['amount']);
        $this->assertSame(true, $data['testmode']);
        $this->assertCount(2, $data);
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testGetDataWithoutAmount()
    {
        $this->request->initialize(
            [
                'apiKey'               => 'mykey',
                'transactionReference' => 'tr_98nUH7v5bT',
                'testmode'             => true,
            ]
        );

        $data = $this->request->getData();

        $this->assertCount(0, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('ConnectRefundSuccess.txt');
        /** @var ConnectRefundResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "POST",
                "https://api.mollie.com/v2/payments/tr_98nUH7v5bT/refunds",
                [],
                '{"amount":{"value":"10.00","currency":"EUR"},"testmode":true}'
            ),
            $this->getMockClient()->getLastRequest()
        );


        $this->assertInstanceOf(RefundResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('tr_98nUH7v5bT', $response->getTransactionReference());
        $this->assertSame('re_c5rGsnjbxz', $response->getTransactionId());
    }

    public function test401Failure()
    {
        $this->setMockHttpResponse('ConnectRefund401Failure.txt');
        /** @var ConnectRefund401Failure $response */
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request("POST", "https://api.mollie.com/v2/payments/tr_98nUH7v5bT/refunds", [], ''),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(RefundResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('{"status":401,"title":"Unauthorized Request","detail":"Missing authentication, or failed to authenticate","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/authentication","type":"text\/html"}}}', $response->getMessage());
    }

    public function test422Failure()
    {
        $this->setMockHttpResponse('ConnectRefund422Failure.txt');
        /** @var ConnectRefundResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "POST",
                "https://api.mollie.com/v2/payments/tr_98nUH7v5bT/refunds",
                [],
                '{"amount":{"value":"10.00","currency":"EUR"},"testmode":true}'
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(RefundResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('{"status":422,"title":"Unprocessable Entity","detail":"The payment method is invalid","field":"method","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/handling-errors","type":"text\/html"}}}', $response->getMessage());
    }
}
