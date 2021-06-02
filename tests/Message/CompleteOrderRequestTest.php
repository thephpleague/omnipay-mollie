<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Mollie\Message\Request\CompleteOrderRequest;
use Omnipay\Mollie\Message\Request\CompletePurchaseRequest;
use Omnipay\Mollie\Message\Response\CompleteOrderResponse;
use Omnipay\Mollie\Message\Response\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;

class CompleteOrderRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var CompletePurchaseRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new CompleteOrderRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'mykey',
        ));

        $this->getHttpRequest()->request->replace(array(
            'id' => 'ord_kEn1PlbGa',
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

        $this->assertSame("ord_kEn1PlbGa", $data['id']);
        $this->assertCount(1, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CompleteOrderSuccess.txt');
        /** @var CompleteOrderResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("GET", "https://api.mollie.com/v2/orders/ord_kEn1PlbGa"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(CompleteOrderResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isOpen());
        $this->assertFalse($response->isPaid());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('ord_kEn1PlbGa', $response->getTransactionReference());
    }

}
