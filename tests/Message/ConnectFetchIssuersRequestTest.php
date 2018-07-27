<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Issuer;
use Omnipay\Mollie\Message\Request\ConnectFetchIssuersRequest;
use Omnipay\Mollie\Message\Response\FetchIssuersResponse;
use Omnipay\Tests\TestCase;

class ConnectFetchIssuersRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var ConnectFetchIssuersRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new ConnectFetchIssuersRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'mykey',
            'profileId' => 'pfl_3RkSN1zuPE',
            'testmode' => true,
        ));
    }

    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertCount(2, $data);

        $this->assertSame('pfl_3RkSN1zuPE', $data['profileId']);
        $this->assertSame(true, $data['testmode']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('ConnectFetchIssuersSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request("GET", "https://api.mollie.com/v2/methods/ideal?include=issuers&profileId=pfl_3RkSN1zuPE&testmode=true"),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(FetchIssuersResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());

        $expectedIssues = array(
            new Issuer('ideal_ABNANL2A', 'ABN AMRO', 'ideal'),
            new Issuer('ideal_ASNBNL21', 'ASN Bank', 'ideal'),
            new Issuer('ideal_BUNQNL2A', 'bunq', 'ideal'),
            new Issuer('ideal_INGBNL2A', 'ING', 'ideal'),
            new Issuer('ideal_KNABNL2H', 'Knab', 'ideal'),
            new Issuer('ideal_MOYONL21', 'Moneyou', 'ideal'),
            new Issuer('ideal_RABONL2U', 'Rabobank', 'ideal'),
            new Issuer('ideal_RBRBNL21', 'RegioBank', 'ideal'),
            new Issuer('ideal_SNSBNL2A', 'SNS Bank', 'ideal'),
            new Issuer('ideal_TRIONL2U', 'Triodos Bank', 'ideal'),
            new Issuer('ideal_FVLBNL22', 'van Lanschot', 'ideal')
        );

        $this->assertEquals($expectedIssues, $response->getIssuers());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('ConnectFetchIssuersFailure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request("GET", "https://api.mollie.com/v2/methods/ideal?include=issuers&profileId=pfl_3RkSN1zuPE&testmode=true"),
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
