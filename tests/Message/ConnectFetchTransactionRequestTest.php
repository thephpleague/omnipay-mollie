<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\ConnectFetchTransactionRequest;
use Omnipay\Mollie\Message\Response\FetchTransactionResponse;
use Omnipay\Tests\TestCase;

class ConnectFetchTransactionRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var ConnectFetchTransactionRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new ConnectFetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'apiKey'               => 'mykey',
                'transactionReference' => 'tr_98nUH7v5bT',
                'testmode'             => true,
            )
        );
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame("tr_98nUH7v5bT", $data['id']);
        $this->assertSame(true, $data['testmode']);
        $this->assertCount(2, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('ConnectFetchTransactionSuccess.txt');
        /** @var ConnectFetchTransactionRequest $response */
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "GET",
                "https://api.mollie.com/v2/payments/tr_98nUH7v5bT?testmode=true"
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isPaid());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isPaidOut());
        $this->assertFalse($response->isRefunded());
        $this->assertFalse($response->isPartialRefunded());
        $this->assertSame("paid", $response->getStatus());
        $this->assertSame('tr_98nUH7v5bT', $response->getTransactionReference());
        $this->assertSame("10.00", $response->getAmount());
    }

    public function testSendExpired()
    {
        $this->setMockHttpResponse('ConnectFetchTransactionExpired.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "GET",
                "https://api.mollie.com/v2/payments/tr_98nUH7v5bT?testmode=true"
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('tr_98nUH7v5bT', $response->getTransactionReference());
        $this->assertTrue($response->isExpired());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('ConnectFetchTransaction404Failure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "GET",
                "https://api.mollie.com/v2/payments/tr_98nUH7v5bT?testmode=true"
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
