<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Item;
use Omnipay\Mollie\Message\Request\FetchOrderRequest;
use Omnipay\Mollie\Message\Request\FetchTransactionRequest;
use Omnipay\Mollie\Message\Response\FetchOrderResponse;
use Omnipay\Mollie\Message\Response\FetchTransactionResponse;
use Omnipay\Tests\TestCase;

class FetchOrderRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var FetchTransactionRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new FetchOrderRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'apiKey'               => 'mykey',
                'transactionReference' => 'ord_kEn1PlbGa',
            )
        );
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame("ord_kEn1PlbGa", $data['id']);
        $this->assertCount(1, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchOrderSuccess.txt');
        /** @var FetchOrderResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "GET",
                "https://api.mollie.com/v2/orders/ord_kEn1PlbGa"
            ),
            $this->getMockClient()->getLastRequest()
        );


        $this->assertInstanceOf(FetchOrderResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isPaid());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isPaidOut());
        $this->assertTrue($response->isRedirect());
        $this->assertFalse($response->isRefunded());
        $this->assertFalse($response->isPartialRefunded());
        $this->assertSame("created", $response->getStatus());
        $this->assertSame('ord_kEn1PlbGa', $response->getTransactionReference());
        $this->assertSame("1027.99", $response->getAmount());

        $this->assertCount(2, $response->getLines());
        $line = $response->getLines()[0];

        $this->assertSame('5702016116977', $line['sku']);
        $this->assertSame(2, $line['quantity']);
        $this->assertSame('0.00', $line['amountShipped']['value']);

        $this->assertCount(2, $response->getItems()->all());

        /** @var Item $item */
        $item = $response->getItems()->all()[0];
        $this->assertSame('5702016116977', $item->getSku());
        $this->assertSame(2, $item->getQuantity());
        $this->assertSame('0.00',  $item->getAmountShipped());

        // We cannot parse _links, rest should match
        unset($line['_links']);
        $this->assertSame(array_keys($line),  array_keys($item->getParameters()));
    }

}
