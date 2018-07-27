<?php
namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\ConnectPurchaseRequest;
use Omnipay\Mollie\Message\Response\PurchaseResponse;
use Omnipay\Tests\TestCase;

class ConnectPurchaseRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var ConnectPurchaseRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new ConnectPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'mykey',
            "amount" => "10.00",
            "currency" => "EUR",
            "description" => "My first Payment",
            "returnUrl" => "https://webshop.example.org/mollie-return.php",
            "profileId" => "pfl_3RkSN1zuPE",
            'testmode' => true,
        ));
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $this->request->initialize([
            'apiKey' => 'mykey',
            "amount" => "10.00",
            "currency" => "EUR",
            "description" => "My first Payment",
            "returnUrl" => "https://webshop.example.org/mollie-return.php",
            "profileId" => "pfl_3RkSN1zuPE",
            'testmode' => true,
        ]);

        $data = $this->request->getData();

        $this->assertSame(["value" => "10.00", "currency" => "EUR"], $data['amount']);
        $this->assertSame("My first Payment", $data['description']);
        $this->assertSame('https://webshop.example.org/mollie-return.php', $data['redirectUrl']);
        $this->assertCount(7, $data);
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetDataWithWebhook()
    {
        $this->request->initialize(array(
            'apiKey' => 'mykey',
            "amount" => "10.00",
            "currency" => "EUR",
            "description" => "My first Payment",
            "returnUrl" => "https://webshop.example.org/mollie-return.php",
            "profileId" => "pfl_3RkSN1zuPE",
            'testmode' => true,
            'notifyUrl' => 'https://www.example.com/hook',
        ));

        $data = $this->request->getData();

        $this->assertSame(["value" => "10.00", "currency" => "EUR"], $data['amount']);
        $this->assertSame('My first Payment', $data['description']);
        $this->assertSame('https://webshop.example.org/mollie-return.php', $data['redirectUrl']);
        $this->assertSame('https://www.example.com/hook', $data['webhookUrl']);
        $this->assertCount(8, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('ConnectPurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                "POST",
                "https://api.mollie.com/v2/payments",
                [],
                '{
                   "amount":{
                      "value":"10.00",
                      "currency":"EUR"
                   },
                   "description":"My first Payment",
                   "redirectUrl":"https:\/\/webshop.example.org\/mollie-return.php",
                   "method":null,
                   "metadata":null,
                   "profileId":"pfl_3RkSN1zuPE",
                   "testmode":true
                }'
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertSame('https://www.mollie.com/payscreen/select-method/jdsTnrTT3R', $response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertSame('tr_jdsTnrTT3R', $response->getTransactionReference());
        $this->assertTrue($response->isOpen());
        $this->assertFalse($response->isPaid());
        $this->assertNull($response->getCode());
        $this->assertJsonStringEqualsJsonString(
            '{"resource":"payment","id":"tr_jdsTnrTT3R","mode":"test","createdAt":"2018-07-27T07:10:14+00:00","amount":{"value":"10.00","currency":"EUR"},"description":"My first Payment","method":null,"metadata":null,"status":"open","isCancelable":false,"expiresAt":"2018-07-27T07:25:14+00:00","profileId":"pfl_3RkSN1zuPE","sequenceType":"oneoff","redirectUrl":"https://webshop.example.org/mollie-return.php","_links":{"self":{"href":"https://api.mollie.com/v2/payments/tr_jdsTnrTT3R","type":"application/hal+json"},"checkout":{"href":"https://www.mollie.com/payscreen/select-method/jdsTnrTT3R","type":"text/html"},"documentation":{"href":"https://docs.mollie.com/reference/v2/payments-api/create-payment","type":"text/html"}}}',
            $response->getMessage()
        );
    }

    public function testIssuerFailure()
    {
        $this->setMockHttpResponse('ConnectPurchaseIssuerFailure.txt');
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
