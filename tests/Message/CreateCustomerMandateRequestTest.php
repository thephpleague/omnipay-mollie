<?php
/**
 * Date: 21/08/17
 * Time: 13:20
 */

namespace Omnipay\Mollie\Message;

use Omnipay\Tests\TestCase;

class CreateCustomerMandateRequestTest extends TestCase
{
    /**
     * @var CreateCustomerMandateRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new CreateCustomerMandateRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(array(
            'apiKey' => "mykey",
            'consumerName' => "Customer A",
            'consumerAccount' => "NL53INGB0000000000",
            "method" => "directdebit",
            'customerReference' => 'cst_bSNBBJBzdG',
            'mandateReference' => "YOUR-COMPANY-MD13804"
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame("cst_bSNBBJBzdG", $data['customerReference']);
        $this->assertSame("NL53INGB0000000000", $data['consumerAccount']);
        $this->assertSame('directdebit', $data['method']);
        $this->assertSame("YOUR-COMPANY-MD13804", $data['mandateReference']);

        $this->assertCount(5, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CreateCustomerMandateSuccess.txt');

        /** @var \Omnipay\Mollie\Message\CreateCustomerResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\CreateCustomerMandateResponse', $response);
        $this->assertSame('cst_bSNBBJBzdG', $response->getCustomerReference());
        $this->assertSame("mdt_pWUnw6pkBN", $response->getMandateId());

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CreateCustomerMandateFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Unauthorized request', $response->getMessage());
    }
}