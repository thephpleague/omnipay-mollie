<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\FetchTransactionRequest;
use Omnipay\Mollie\Message\Response\FetchTransactionResponse;
use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var FetchTransactionRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'apiKey'               => 'mykey',
                'transactionReference' => 'tr_WDqYK6vllg',
            )
        );
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame("tr_WDqYK6vllg", $data['id']);
        $this->assertCount(1, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');
        /** @var FetchTransactionResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "GET",
                "https://api.mollie.com/v2/payments/tr_WDqYK6vllg"
            ),
            $this->getMockClient()->getLastRequest()
        );


        $this->assertInstanceOf(FetchTransactionResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isPaid());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isPaidOut());
        $this->assertTrue($response->isRedirect());
        $this->assertFalse($response->isRefunded());
        $this->assertFalse($response->isPartialRefunded());
        $this->assertSame("paid", $response->getStatus());
        $this->assertSame('tr_WDqYK6vllg', $response->getTransactionReference());
        $this->assertSame("10.00", $response->getAmount());
    }

    public function testSendExpired()
    {
        $this->setMockHttpResponse('FetchTransactionExpired.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "GET",
                "https://api.mollie.com/v2/payments/tr_WDqYK6vllg"
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('tr_WDqYK6vllg', $response->getTransactionReference());
        $this->assertTrue($response->isExpired());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchTransaction404Failure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "GET",
                "https://api.mollie.com/v2/payments/tr_WDqYK6vllg"
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertEquals(404, $response->getStatus());
        $this->assertNull($response->getAmount());
    }
}
