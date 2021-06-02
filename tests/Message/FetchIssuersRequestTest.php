<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Issuer;
use Omnipay\Mollie\Message\Request\FetchIssuersRequest;
use Omnipay\Mollie\Message\Response\FetchIssuersResponse;
use Omnipay\Tests\TestCase;

class FetchIssuersRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var FetchIssuersRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new FetchIssuersRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'mykey'
        ));
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertEmpty($data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchIssuersSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request("GET", "https://api.mollie.com/v2/methods/ideal?include=issuers"),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(FetchIssuersResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());

        $expectedIssuer = new Issuer('ideal_ABNANL2A', 'ABN AMRO', 'ideal');
        $expectedIssuer2 = new Issuer('ideal_ASNBNL21', 'ASN Bank', 'ideal');

        $this->assertEquals(array($expectedIssuer, $expectedIssuer2), $response->getIssuers());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchIssuersFailure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request("GET", "https://api.mollie.com/v2/methods/ideal?include=issuers"),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(FetchIssuersResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('{"status":401,"title":"Unauthorized Request","detail":"Missing authentication, or failed to authenticate","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/authentication","type":"text\/html"}}}', $response->getMessage());
        $this->assertEmpty($response->getIssuers());
    }

}
