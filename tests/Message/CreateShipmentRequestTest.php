<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Item;
use Omnipay\Mollie\Message\Request\CreateCustomerRequest;
use Omnipay\Mollie\Message\Request\CreateShipmentRequest;
use Omnipay\Mollie\Message\Response\CreateCustomerResponse;
use Omnipay\Mollie\Message\Response\CreateShipmentResponse;
use Omnipay\Tests\TestCase;

class CreateShipmentRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     *
     * @var CreateCustomerRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new CreateShipmentRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(array(
            'apiKey' => 'mykey',
            'transactionReference' => 'ord_xxx',
            'items' => [
                [
                    'id' => 'odl_dgtxyl',
                    'quantity' => 1,
                ],
                [
                    'id' => 'odl_jp31jz',
                ]
            ],
            'tracking' => [
                'carrier' => 'PostNL',
                'code' => '3SKABA000000000',
                'url' => 'http://postnl.nl/tracktrace/?B=3SKABA000000000&P=1016EE&D=NL&T=C',
            ]
        ));
    }

    /**
     * @throws InvalidRequestException
     */
    public function testData()
    {
        $data = $this->request->getData();

        $this->assertSame("odl_dgtxyl", $data['lines'][0]['id']);
        $this->assertSame(1, $data['lines'][0]['quantity']);
        $this->assertCount(2, $data['lines'][0]);
        $this->assertSame("odl_jp31jz", $data['lines'][1]['id']);
        $this->assertCount(1, $data['lines'][1]);

        $this->assertSame([
            'carrier' => 'PostNL',
            'code' => '3SKABA000000000',
            'url' => 'http://postnl.nl/tracktrace/?B=3SKABA000000000&P=1016EE&D=NL&T=C',
        ], $data['tracking']);

        $this->assertCount(2, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CreateShipmentSuccess.txt');

        /** @var CreateShipmentResponse $response */
        $response = $this->request->send();

        $orderId = $this->request->getTransactionReference();

        $this->assertEqualRequest(
            new Request(
                "POST",
                "https://api.mollie.com/v2/orders/{$orderId}/shipments",
                [],
                '{
                     "lines": [
                         {
                             "id": "odl_dgtxyl",
                             "quantity": 1
                         },
                         {
                             "id": "odl_jp31jz"
                         }
                     ],
                     "tracking": {
                         "carrier": "PostNL",
                         "code": "3SKABA000000000",
                         "url": "http://postnl.nl/tracktrace/?B=3SKABA000000000&P=1016EE&D=NL&T=C"
                     }
                 }'
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(CreateShipmentResponse::class, $response);
        $this->assertSame('shp_3wmsgCJN4U', $response->getTransactionReference());

        $this->assertTrue($response->isSuccessful());

        $this->assertCount(2, $response->getLines());
        $line = $response->getLines()[0];

        $this->assertSame('5702016116977', $line['sku']);
        $this->assertSame(1, $line['quantity']);
        $this->assertSame('299.00', $line['totalAmount']['value']);

        $this->assertCount(2, $response->getItems()->all());

        /** @var Item $item */
        $item = $response->getItems()->all()[0];
        $this->assertSame('5702016116977', $item->getSku());
        $this->assertSame(1, $item->getQuantity());
        $this->assertSame('299.00',  $item->getTotalAmount());

        // We cannot parse _links, rest should match
        unset($line['_links']);
        $this->assertSame(array_keys($line),  array_keys($item->getParameters()));

    }

}
