<?php

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Request\RevokeCustomerMandateRequest;
use Omnipay\Mollie\Message\Response\RevokeCustomerMandateResponse;
use Omnipay\Tests\TestCase;

class RevokeCustomerMandateRequestTest extends TestCase
{
    use AssertRequestTrait;

    /**
     * @var RevokeCustomerMandateRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new RevokeCustomerMandateRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize([
            'apiKey' => "mykey",
            'customerReference' => 'cst_bSNBBJBzdG',
            'mandateId' => "mdt_pWUnw6pkBN",
        ]);
    }

    /**
     * @throws InvalidRequestException
     */
    public function testData()
    {
        $this->request->initialize([
            'apiKey' => "mykey",
            'customerReference' => 'cst_bSNBBJBzdG',
            'mandateId' => "mdt_pWUnw6pkBN",
        ]);

        $data = $this->request->getData();
        $this->assertSame("cst_bSNBBJBzdG", $data['customerReference']);
        $this->assertSame("mdt_pWUnw6pkBN", $data['mandateId']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RevokeCustomerMandateSuccess.txt');

        /** @var RevokeCustomerMandateResponse $response */
        $response = $this->request->send();

        $this->assertEqualRequest(new Request("DELETE", "https://api.mollie.com/v2/customers/cst_bSNBBJBzdG/mandates/mdt_pWUnw6pkBN"), $this->getMockClient()->getLastRequest());

        $this->assertInstanceOf(RevokeCustomerMandateResponse::class, $response);

        $this->assertTrue($response->isSuccessful());
    }
}
