<?php
namespace Omnipay\Mollie\Message;

use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{

    /**
     *
     * @var \Omnipay\Mollie\Message\PurchaseRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'apiKey'               => 'mykey',
                'transactionReference' => 'tr_WDqYK6vllg'
            )
        );
    }

    public function testGetData()
    {
        $this->request->initialize(
            array(
                'apiKey'               => 'mykey',
                'amount'               => '12.00',
                'transactionReference' => 'tr_WDqYK6vllg'
            )
        );

        $data = $this->request->getData();

        $this->assertSame("12.00", $data['amount']);
        $this->assertCount(1, $data);
    }

    public function testGetDataWithoutAmount()
    {
        $this->request->initialize(
            array(
                'apiKey'               => 'mykey',
                'transactionReference' => 'tr_WDqYK6vllg'
            )
        );

        $data = $this->request->getData();

        $this->assertCount(0, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\RefundResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('tr_WDqYK6vllg', $response->getTransactionReference());
        $this->assertSame('re_4qqhO89gsT', $response->getTransactionId());
    }
}
