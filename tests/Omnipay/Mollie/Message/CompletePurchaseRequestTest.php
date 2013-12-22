<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'partnerId' => 'my partner id',
        ));
    }

    public function testGetData()
    {
        $this->getHttpRequest()->query->replace(array(
            'transaction_id' => 'abc123',
        ));

        $data = $this->request->getData();

        $this->assertSame('check', $data['a']);
        $this->assertSame('my partner id', $data['partnerid']);
        $this->assertSame('abc123', $data['transaction_id']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('3be9b120ea75fe6807571f5f649cad6d', $response->getTransactionReference());
        $this->assertSame('This iDEAL-order has successfuly been payed for, and this is the first time you check it.', $response->getMessage());
        $this->assertSame('Success', $response->getCode());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CompletePurchaseFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('d0feefce2a1ae5a05d24a10d364bc281', $response->getTransactionReference());
        $this->assertSame('This iDEAL-order wasn\'t payed for, or was already checked by you. (We give payed=true only once, for your protection)', $response->getMessage());
        $this->assertSame('CheckedBefore', $response->getCode());
    }
}
