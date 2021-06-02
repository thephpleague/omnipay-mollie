<?php
namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Mollie\Message\Request\PurchaseRequest;
use Omnipay\Mollie\Message\Request\RefundRequest;
use Omnipay\Mollie\Message\Response\RefundResponse;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var PurchaseRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'apiKey' => 'mykey',
            'transactionReference' => 'tr_WDqYK6vllg',
            'amount' => '12.00',
            'currency' => 'EUR',
        ]);
    }

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testGetData()
    {
        $this->request->initialize([
            'apiKey' => 'mykey',
            'amount' => '12.00',
            'currency' => 'EUR',
            'transactionReference' => 'tr_WDqYK6vllg'
        ]);

        $data = $this->request->getData();

        $this->assertSame(["value" => "12.00", "currency" => "EUR"], $data['amount']);
        $this->assertCount(1, $data);
    }

    public function testGetDataWithoutAmount()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);

        $this->request->initialize(
            [
                'apiKey'               => 'mykey',
                'transactionReference' => 'tr_WDqYK6vllg',
            ]
        );

        $data = $this->request->getData();

        $this->assertCount(0, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');
        /** @var RefundResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "POST",
                "https://api.mollie.com/v2/payments/tr_WDqYK6vllg/refunds",
                [],
                '{"amount":{"value":"12.00","currency":"EUR"}}'
            ),
            $this->getMockClient()->getLastRequest()
        );


        $this->assertInstanceOf(RefundResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('tr_WDqYK6vllg', $response->getTransactionReference());
        $this->assertSame('re_4qqhO89gsT', $response->getTransactionId());
    }

    public function test401Failure()
    {
        $this->setMockHttpResponse('Refund401Failure.txt');
        /** @var RefundResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request("POST", "https://api.mollie.com/v2/payments/tr_WDqYK6vllg/refunds", [], ''),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(RefundResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('{"status":401,"title":"Unauthorized Request","detail":"Missing authentication, or failed to authenticate","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/authentication","type":"text\/html"}}}', $response->getMessage());
    }

    public function test422Failure()
    {
        $this->setMockHttpResponse('Refund422Failure.txt');
        /** @var RefundResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "POST",
                "https://api.mollie.com/v2/payments/tr_WDqYK6vllg/refunds",
                [],
                '{"amount":{"value":"12.00","currency":"EUR"}}'
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(RefundResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('{"status":422,"title":"Unprocessable Entity","detail":"The payment method is invalid","field":"method","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/handling-errors","type":"text\/html"}}}', $response->getMessage());
    }
}
