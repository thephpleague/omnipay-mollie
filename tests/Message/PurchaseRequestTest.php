<?php
namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\PurchaseRequest;
use Omnipay\Mollie\Message\Response\PurchaseResponse;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var PurchaseRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey'       => 'mykey',
            'amount'       => '12.00',
            'currency'     => 'USD',
            'issuer'       => 'my bank',
            'description'  => 'Description',
            'returnUrl'    => 'https://www.example.com/return',
            'method'       => 'ideal',
            'metadata' => ['meta'],
            'locale'       => 'fr_FR',
            'billingEmail' => 'billing-email@example.com',
        ));
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $this->request->initialize([
            'apiKey' => 'mykey',
            'amount' => '12.00',
            'currency' => 'USD',
            'description' => 'Description',
            'returnUrl' => 'https://www.example.com/return',
            'paymentMethod' => 'ideal',
            'metadata' => ['meta'],
            'issuer' => 'my bank',
            'locale' => 'fr_FR',
            'billingEmail' => 'billing-email@example.com',
        ]);

        $data = $this->request->getData();

        $this->assertSame(["value" => "12.00", "currency" => "USD"], $data['amount']);
        $this->assertSame('Description', $data['description']);
        $this->assertSame('https://www.example.com/return', $data['redirectUrl']);
        $this->assertSame('ideal', $data['method']);
        $this->assertSame(['meta'], $data['metadata']);
        $this->assertSame('my bank', $data['issuer']);
        $this->assertSame('fr_FR', $data['locale']);
        $this->assertSame('billing-email@example.com', $data['billingEmail']);
        $this->assertCount(8, $data);
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetDataWithWebhook()
    {
        $this->request->initialize(array(
            'apiKey'        => 'mykey',
            'amount'        => '12.00',
            'currency'      => 'EUR',
            'description'   => 'Description',
            'returnUrl'     => 'https://www.example.com/return',
            'paymentMethod' => 'ideal',
            'metadata' => ['meta'],
            'issuer'        => 'my bank',
            'locale'        => 'fr_FR',
            'billingEmail'  => 'billing-email@example.com',
            'notifyUrl'     => 'https://www.example.com/hook',
        ));

        $data = $this->request->getData();

        $this->assertSame(["value" => "12.00", "currency" => "EUR"], $data['amount']);
        $this->assertSame('Description', $data['description']);
        $this->assertSame('https://www.example.com/return', $data['redirectUrl']);
        $this->assertSame('ideal', $data['method']);
        $this->assertSame(['meta'], $data['metadata']);
        $this->assertSame('my bank', $data['issuer']);
        $this->assertSame('fr_FR', $data['locale']);
        $this->assertSame('billing-email@example.com', $data['billingEmail']);
        $this->assertSame('https://www.example.com/hook', $data['webhookUrl']);
        $this->assertCount(9, $data);
    }

    /**
     * @throws InvalidRequestException
     */
    public function testNoIssuer()
    {
        $this->request->initialize(array(
            'apiKey'        => 'mykey',
            'amount'        => '12.00',
            'currency'      => 'SEK',
            'description'   => 'Description',
            'returnUrl'     => 'https://www.example.com/return',
            'paymentMethod' => 'ideal',
            'metadata' => ['meta'],
            'locale'        => 'fr_FR',
            'billingEmail'  => 'billing-email@example.com',
            'notifyUrl'     => 'https://www.example.com/hook',
        ));

        $data = $this->request->getData();

        $this->assertSame(["value" => "12.00", "currency" => "SEK"], $data['amount']);
        $this->assertSame('Description', $data['description']);
        $this->assertSame('https://www.example.com/return', $data['redirectUrl']);
        $this->assertSame('ideal', $data['method']);
        $this->assertSame(['meta'], $data['metadata']);
        $this->assertSame('fr_FR', $data['locale']);
        $this->assertSame('billing-email@example.com', $data['billingEmail']);
        $this->assertSame('https://www.example.com/hook', $data['webhookUrl']);
        $this->assertCount(8, $data);
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetDataWithLinesAddressesAndCaptureMode()
    {
        $this->request->initialize([
            'apiKey' => 'mykey',
            'amount' => '1027.99',
            'currency' => 'EUR',
            'description' => 'Order #12345',
            'returnUrl' => 'https://www.example.com/return',
            'paymentMethod' => 'klarna',
            'captureMode' => 'manual',
            'card' => [
                'firstName' => 'Piet',
                'lastName' => 'Mondriaan',
                'email' => 'piet@example.org',
                'address1' => 'Keizersgracht 126',
                'postcode' => '1015 CW',
                'city' => 'Amsterdam',
                'country' => 'NL',
                'shippingFirstName' => 'Piet',
                'shippingLastName' => 'Mondriaan',
                'shippingAddress1' => 'Prinsengracht 126',
                'shippingPostcode' => '1015 EA',
                'shippingCity' => 'Amsterdam',
                'shippingCountry' => 'NL',
            ],
            'items' => [
                new \Omnipay\Mollie\Item([
                    'type' => 'physical',
                    'sku' => '5702016116977',
                    'name' => 'LEGO 42083 Bugatti Chiron',
                    'quantity' => 2,
                    'vatRate' => '21.00',
                    'unitPrice' => '399.00',
                    'totalAmount' => '698.00',
                    'discountAmount' => '100.00',
                    'vatAmount' => '121.14',
                ]),
                [
                    'type' => 'discount',
                    'name' => 'Coupon',
                    'quantity' => 1,
                    'vatRate' => '21.00',
                    'unitPrice' => '-20.00',
                    'totalAmount' => '-20.00',
                    'vatAmount' => '-3.47',
                ],
                [
                    'type' => 'shipping_fee',
                    'name' => 'Shipping',
                    'quantity' => 1,
                    'vatRate' => '21.00',
                    'unitPrice' => '349.99',
                    'totalAmount' => '349.99',
                ],
            ],
        ]);

        $data = $this->request->getData();

        $this->assertSame('manual', $data['captureMode']);
        $this->assertCount(3, $data['lines']);
        $this->assertSame([
            'type' => 'physical',
            'sku' => '5702016116977',
            'description' => 'LEGO 42083 Bugatti Chiron',
            'quantity' => 2,
            'vatRate' => '21.00',
            'unitPrice' => ['currency' => 'EUR', 'value' => '399.00'],
            'totalAmount' => ['currency' => 'EUR', 'value' => '698.00'],
            'discountAmount' => ['currency' => 'EUR', 'value' => '100.00'],
            'vatAmount' => ['currency' => 'EUR', 'value' => '121.14'],
        ], $data['lines'][0]);
        $this->assertSame(['currency' => 'EUR', 'value' => '-20.00'], $data['lines'][1]['totalAmount']);
        $this->assertSame(['currency' => 'EUR', 'value' => '60.74'], $data['lines'][2]['vatAmount']);
        $this->assertSame([
            'streetAndNumber' => 'Keizersgracht 126',
            'city' => 'Amsterdam',
            'postalCode' => '1015 CW',
            'country' => 'NL',
            'givenName' => 'Piet',
            'familyName' => 'Mondriaan',
            'email' => 'piet@example.org',
        ], $data['billingAddress']);
        $this->assertSame('Prinsengracht 126', $data['shippingAddress']['streetAndNumber']);
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetDataWithoutAddressSendsNoAddress()
    {
        $this->request->setCard(['email' => 'piet@example.org']);

        $data = $this->request->getData();

        $this->assertArrayNotHasKey('billingAddress', $data);
        $this->assertArrayNotHasKey('shippingAddress', $data);
        $this->assertArrayNotHasKey('lines', $data);
        $this->assertArrayNotHasKey('captureMode', $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "POST",
                "https://api.mollie.com/v2/payments",
                [],
                '{  
                   "amount":{  
                      "value":"12.00",
                      "currency":"USD"
                   },
                   "description":"Description",
                   "redirectUrl":"https:\/\/www.example.com\/return",
                   "method":null,
                   "metadata":[
                        "meta"
                    ],
                   "issuer":"my bank",
                   "locale":"fr_FR",
                   "billingEmail":"billing-email@example.com"
                }'
            ),
            $this->getMockClient()->getLastRequest()
        );


        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertSame('https://www.mollie.com/payscreen/select-method/7UhSN1zuXS', $response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertSame('tr_7UhSN1zuXS', $response->getTransactionReference());
        $this->assertTrue($response->isOpen());
        $this->assertFalse($response->isPaid());
        $this->assertNull($response->getCode());
        $this->assertJsonStringEqualsJsonString(
            '{"resource":"payment","id":"tr_7UhSN1zuXS","mode":"test","createdAt":"2018-03-20T09:13:37+00:00","amount":{"value":"10.00","currency":"EUR"},"description":"My first payment","method":null,"metadata":{"order_id":"12345"},"status":"open","isCancelable":false,"expiresAt":"2018-03-20T09:28:37+00:00","details":null,"profileId":"pfl_QkEhN94Ba","sequenceType":"oneoff","redirectUrl":"https:\/\/webshop.example.org\/order\/12345\/","webhookUrl":"https:\/\/webshop.example.org\/payments\/webhook\/","_links":{"self":{"href":"https:\/\/api.mollie.com\/v2\/payments\/tr_7UhSN1zuXS","type":"application\/json"},"checkout":{"href":"https:\/\/www.mollie.com\/payscreen\/select-method\/7UhSN1zuXS","type":"text\/html"},"documentation":{"href":"https:\/\/docs.mollie.com\/reference\/v2\/payments-api\/create-payment","type":"text\/html"}}}',
            $response->getMessage()
        );
    }

    public function testSendSuccessWithQrcode()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->setInclude('details.qrCode')->send();

        $this->assertEqualRequest(
            new Request(
                "POST",
                "https://api.mollie.com/v2/payments?include=details.qrCode",
                [],
                '{  
                   "amount":{  
                      "value":"12.00",
                      "currency":"USD"
                   },
                   "description":"Description",
                   "redirectUrl":"https:\/\/www.example.com\/return",
                   "method":null,
                   "metadata":[
                        "meta"
                    ],
                   "issuer":"my bank",
                   "locale":"fr_FR",
                   "billingEmail":"billing-email@example.com"
                }'
            ),
            $this->getMockClient()->getLastRequest()
        );


        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
    }

    public function testIssuerFailure()
    {
        $this->setMockHttpResponse('PurchaseIssuerFailure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "POST",
                "https://api.mollie.com/v2/payments"
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertSame('{"status":422,"title":"Unprocessable Entity","detail":"The payment method is invalid","field":"method","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/handling-errors","type":"text\/html"}}}', $response->getMessage());
    }
}
