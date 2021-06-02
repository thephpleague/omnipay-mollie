<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\PaymentMethod;
use Omnipay\Mollie\Message\Request\FetchPaymentMethodsRequest;
use Omnipay\Mollie\Message\Response\FetchPaymentMethodsResponse;
use Omnipay\Tests\TestCase;

class FetchPaymentMethodsRequestTest extends TestCase
{
    use AssertRequestTrait;

    protected static $expectedRequestUri = 'https://api.mollie.com/v2/methods?amount%5Bvalue%5D=22.56&amount%5Bcurrency%5D=SEK&billingCountry=SE&locale=sv_SE&resource=orders&includeWallets=applepay&sequenceType=oneoff';

    /**
     * @var FetchPaymentMethodsRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new FetchPaymentMethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'apiKey' => 'mykey',
            'amount' => '22.56',
            'billingCountry' => 'SE',
            'currency' => 'SEK',
            'locale' => 'sv_SE',
            'resource' => 'orders',
            'sequenceType' => 'oneoff',
            'includeWallets' => 'applepay',
        ]);
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('SEK', $data['amount']['currency']);
        $this->assertSame('22.56', $data['amount']['value']);
        $this->assertSame('SE', $data['billingCountry']);
        $this->assertSame('sv_SE', $data['locale']);
        $this->assertSame('orders', $data['resource']);
        $this->assertSame('oneoff', $data['sequenceType']);
        $this->assertSame('applepay', $data['includeWallets']);
    }

    /**
     * @throws InvalidRequestException
     */
    public function testOptionalParameters()
    {
        $this->request->initialize([
            'apiKey' => 'mykey',
        ]);
        $this->assertEmpty(array_filter($this->request->getData()));
        $this->request->send();
        $this->assertEqualRequest(
            new Request('GET', 'https://api.mollie.com/v2/methods'),
            $this->getMockClient()->getLastRequest()
        );
    }

    /**
     * Require both amount and currency when either one is set.
     *
     * @throws InvalidRequestException
     */
    public function testRequiredAmountParameters()
    {
        $this->expectException(InvalidRequestException::class);

        $this->request->initialize([
            'apiKey' => 'mykey',
            'amount' => '78.02',
        ]);
        $this->request->getData();
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchPaymentMethodsSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request('GET', self::$expectedRequestUri),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(FetchPaymentMethodsResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $paymentMethods = $response->getPaymentMethods();
        $this->assertCount(12, $paymentMethods);

        $paymentMethod = new PaymentMethod('ideal', 'iDEAL');

        $this->assertEquals($paymentMethod, $paymentMethods[0]);
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchPaymentMethodsFailure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request('GET', self::$expectedRequestUri),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(FetchPaymentMethodsResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('{"status":401,"title":"Unauthorized Request","detail":"Missing authentication, or failed to authenticate","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/authentication","type":"text\/html"}}}', $response->getMessage());
        $this->assertEmpty($response->getPaymentMethods());
    }
}
