<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Mollie\PaymentMethod;
use Omnipay\Tests\TestCase;

class FetchPaymentMethodsRequestTest extends TestCase
{
    /**
     * @var \Omnipay\Mollie\Message\FetchPaymentMethodsRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new FetchPaymentMethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'mykey'
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertEmpty($data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchPaymentMethodsSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchPaymentMethodsResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $paymentMethods = $response->getPaymentMethods();
        $this->assertCount(4, $paymentMethods);

        $expectedPaymentMethod = new PaymentMethod(
            'ideal',
            'iDEAL',
            array(
                'minimum' => 0.43,
                'maximum' => 50000.00
            ),
            array(
                'normal' => 'https://www.mollie.nl/images/payscreen/methods/ideal.png',
                'bigger' => 'https://www.mollie.nl/images/payscreen/methods/ideal@2x.png'
            )
        );

        $this->assertEquals($expectedPaymentMethod, $paymentMethods[0]);
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchPaymentMethodsFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchPaymentMethodsResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Unauthorized request', $response->getMessage());
        $this->assertEmpty($response->getPaymentMethods());
    }
}
