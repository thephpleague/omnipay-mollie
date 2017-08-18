<?php
/**
 * Date: 18/08/17
 * Time: 12:39
 */

namespace Omnipay\Mollie\Message;

use Omnipay\Tests\TestCase;


class FetchCustomerMandatesRequestTest extends TestCase
{
    /**
     * @var FetchCustomerMandatesRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new FetchCustomerMandatesRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'apiKey'            => 'mykey',
                'customerReference' => 'cst_R6JLAuqEgm',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertEmpty($data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse("FetchCustomerMandatesSuccess.txt");

        $response = $this->request->send();

        $this->assertInstanceOf(FetchCustomerMandatesResponse::class, $response);

        $mandates = $response->getMandates();

        $this->assertSame("mdt_pO2m5jVgMa", $mandates[0]['id']);
        $this->assertSame("mdt_qtUgejVgMN", $mandates[1]['id']);
        $this->assertSame("directdebit", $mandates[0]['method']);

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchCustomerMandatesFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame("The customer id is invalid", $response->getMessage());
    }
}