<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\CancelPaymentRequest;
use Omnipay\Mollie\Message\Response\CancelPaymentResponse;
use Omnipay\Tests\TestCase;

class CancelPaymentRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var CancelPaymentRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new CancelPaymentRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'apiKey' => 'mykey',
            'transactionReference' => 'tr_WDqYK6vllg',
        ]);
    }

    public function testGetData()
    {
        $this->assertSame([], $this->request->getData());
    }

    public function testGetDataRequiresTransactionReference()
    {
        $this->expectException(InvalidRequestException::class);

        $this->request->setTransactionReference(null);
        $this->request->getData();
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CancelPaymentSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request('DELETE', 'https://api.mollie.com/v2/payments/tr_WDqYK6vllg'),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(CancelPaymentResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isCancelled());
        $this->assertSame('tr_WDqYK6vllg', $response->getTransactionReference());
    }
}
