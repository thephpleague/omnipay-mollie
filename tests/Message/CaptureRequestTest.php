<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\CaptureRequest;
use Omnipay\Mollie\Message\Response\CaptureResponse;
use Omnipay\Tests\TestCase;

class CaptureRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var CaptureRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'apiKey' => 'mykey',
            'transactionReference' => 'tr_WDqYK6vllg',
        ]);
    }

    public function testGetDataFullAmount()
    {
        $this->assertSame([], $this->request->getData());
    }

    public function testGetDataPartialAmount()
    {
        $this->request->setAmount('10.00');
        $this->request->setCurrency('EUR');
        $this->request->setDescription('Partial capture');

        $this->assertSame([
            'amount' => ['value' => '10.00', 'currency' => 'EUR'],
            'description' => 'Partial capture',
        ], $this->request->getData());
    }

    public function testGetDataRequiresTransactionReference()
    {
        $this->expectException(InvalidRequestException::class);

        $this->request->setTransactionReference(null);
        $this->request->getData();
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request('POST', 'https://api.mollie.com/v2/payments/tr_WDqYK6vllg/captures'),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(CaptureResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('tr_WDqYK6vllg', $response->getTransactionReference());
        $this->assertSame('cpt_mNepDkEtco6ah3QNPUGYH', $response->getCaptureReference());
        $this->assertSame('pending', $response->getStatus());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CaptureFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CaptureResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getTransactionReference());
        $this->assertStringContainsString('cannot be captured', $response->getMessage());
    }
}
