<?php
/**
 * Date: 21/08/17
 * Time: 12:10
 */

namespace Omnipay\Mollie\Message;

use Omnipay\Tests\TestCase;

class RevokeCustomerMandateRequestTest extends TestCase
{
    /**
     * @var RevokeCustomerMandateRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new RevokeCustomerMandateRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize([
            'apiKey' => "mykey",
            'customerReference' => 'cst_bSNBBJBzdG',
            'mandateId' => "mdt_pWUnw6pkBN",
        ]);
    }

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

        /** @var \Omnipay\Mollie\Message\RevokeCustomerMandateResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\RevokeCustomerMandateResponse', $response);

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }
}