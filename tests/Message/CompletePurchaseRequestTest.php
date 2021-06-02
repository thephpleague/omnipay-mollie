<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Mollie\Message\Request\CompletePurchaseRequest;
use Omnipay\Mollie\Message\Response\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var CompletePurchaseRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'mykey',
        ));

        $this->getHttpRequest()->request->replace(array(
            'id' => 'tr_Qzin4iTWrU',
        ));
    }

    public function testGetDataWithoutIDParameter()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage('The transactionReference parameter is required');

        $this->getHttpRequest()->request->remove('id');

        $data = $this->request->getData();

        $this->assertEmpty($data);
    }

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame("tr_Qzin4iTWrU", $data['id']);
        $this->assertCount(1, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/payments/tr_Qzin4iTWrU"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(CompletePurchaseResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isOpen());
        $this->assertTrue($response->isPaid());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('tr_Qzin4iTWrU', $response->getTransactionReference());
    }

    public function testSendExpired()
    {
        $this->setMockHttpResponse('CompletePurchaseExpired.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/payments/tr_Qzin4iTWrU"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(CompletePurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isPaid());
        $this->assertTrue($response->isExpired());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('tr_Qzin4iTWrU', $response->getTransactionReference());
    }
}
