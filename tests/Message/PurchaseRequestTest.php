<?php
namespace Omnipay\Mollie\Test\Message;

use Omnipay\Mollie\Message\PurchaseRequest;
use Omnipay\Mollie\Message\PurchaseResponse;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var PurchaseRequest
     */
    protected $request;

    public function setUp()
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
            'metadata'     => 'meta',
            'locale'       => 'fr_FR',
            'billingEmail' => 'billing-email@example.com',
        ));
    }

    public function testGetData()
    {
        $this->request->initialize([
            'apiKey' => 'mykey',
            'amount' => '12.00',
            'currency' => 'USD',
            'description' => 'Description',
            'returnUrl' => 'https://www.example.com/return',
            'paymentMethod' => 'ideal',
            'metadata' => 'meta',
            'issuer' => 'my bank',
            'locale' => 'fr_FR',
            'billingEmail' => 'billing-email@example.com',
        ]);

        $data = $this->request->getData();

        $this->assertSame(["value" => "12.00", "currency" => "USD"], $data['amount']);
        $this->assertSame('Description', $data['description']);
        $this->assertSame('https://www.example.com/return', $data['redirectUrl']);
        $this->assertSame('ideal', $data['method']);
        $this->assertSame('meta', $data['metadata']);
        $this->assertSame('my bank', $data['issuer']);
        $this->assertSame('fr_FR', $data['locale']);
        $this->assertSame('billing-email@example.com', $data['billingEmail']);
        $this->assertCount(8, $data);
    }

    public function testGetDataWithWebhook()
    {
        $this->request->initialize(array(
            'apiKey'        => 'mykey',
            'amount'        => '12.00',
            'currency'      => 'EUR',
            'description'   => 'Description',
            'returnUrl'     => 'https://www.example.com/return',
            'paymentMethod' => 'ideal',
            'metadata'      => 'meta',
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
        $this->assertSame('meta', $data['metadata']);
        $this->assertSame('my bank', $data['issuer']);
        $this->assertSame('fr_FR', $data['locale']);
        $this->assertSame('billing-email@example.com', $data['billingEmail']);
        $this->assertSame('https://www.example.com/hook', $data['webhookUrl']);
        $this->assertCount(9, $data);
    }

    public function testNoIssuer()
    {
        $this->request->initialize(array(
            'apiKey'        => 'mykey',
            'amount'        => '12.00',
            'currency'      => 'SEK',
            'description'   => 'Description',
            'returnUrl'     => 'https://www.example.com/return',
            'paymentMethod' => 'ideal',
            'metadata'      => 'meta',
            'locale'        => 'fr_FR',
            'billingEmail'  => 'billing-email@example.com',
            'notifyUrl'     => 'https://www.example.com/hook',
        ));

        $data = $this->request->getData();

        $this->assertSame(["value" => "12.00", "currency" => "SEK"], $data['amount']);
        $this->assertSame('Description', $data['description']);
        $this->assertSame('https://www.example.com/return', $data['redirectUrl']);
        $this->assertSame('ideal', $data['method']);
        $this->assertSame('meta', $data['metadata']);
        $this->assertSame('fr_FR', $data['locale']);
        $this->assertSame('billing-email@example.com', $data['billingEmail']);
        $this->assertSame('https://www.example.com/hook', $data['webhookUrl']);
        $this->assertCount(8, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new \GuzzleHttp\Psr7\Request(
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
                   "metadata":"meta",
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
        $this->assertNull($response->getMessage());
    }

    public function testIssuerFailure()
    {
        $this->setMockHttpResponse('PurchaseIssuerFailure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new \GuzzleHttp\Psr7\Request(
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
