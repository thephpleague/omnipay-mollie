<?php

namespace Omnipay\Mollie;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\Mollie\Gateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testFetchIssuers()
    {
        $request = $this->gateway->fetchIssuers();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchIssuersRequest', $request);
    }

    public function testFetchPaymentMethods()
    {
        $request = $this->gateway->fetchPaymentMethods();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchPaymentMethodsRequest', $request);
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Mollie\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testPurchaseReturn()
    {
        $request = $this->gateway->completePurchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Mollie\Message\CompletePurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testRefund()
    {
        $request = $this->gateway->refund(
            array(
                'apiKey'               => 'key',
                'transactionReference' => 'tr_Qzin4iTWrU'
            )
        );

        $this->assertInstanceOf('Omnipay\Mollie\Message\RefundRequest', $request);
        $data = $request->getData();
        $this->assertFalse(array_key_exists('amount', $data));
        $request = $this->gateway->refund(
            array(
                'apiKey'               => 'key',
                'transactionReference' => 'tr_Qzin4iTWrU',
                'amount'               => '10.00'
            )
        );

        $this->assertInstanceOf('Omnipay\Mollie\Message\RefundRequest', $request);
        $data = $request->getData();
        $this->assertSame('10.00', $data['amount']);
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction(
            array(
                'apiKey'               => 'key',
                'transactionReference' => 'tr_Qzin4iTWrU'
            )
        );

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchTransactionRequest', $request);

        $data = $request->getData();
        $this->assertSame('tr_Qzin4iTWrU', $data['id']);
    }

    public function testCreateCustomer()
    {
        $request = $this->gateway->createCustomer(
            array(
                'description'  => 'Test name',
                'email'        => 'test@example.com',
                'metadata'     => 'Something something something dark side.',
                'locale'       => 'nl_NL'
            )
        );

        $this->assertInstanceOf('Omnipay\Mollie\Message\CreateCustomerRequest', $request);
    }

    public function testUpdateCustomer()
    {
        $request = $this->gateway->updateCustomer(
            array(
                'customerReference' => 'cst_bSNBBJBzdG',
                'description'       => 'Test name2',
                'email'             => 'test@example.com',
                'metadata'          => 'Something something something dark side.',
                'locale'            => 'nl_NL'
            )
        );

        $this->assertInstanceOf('Omnipay\Mollie\Message\UpdateCustomerRequest', $request);

        $data = $request->getData();

        $this->assertSame('cst_bSNBBJBzdG', $data['id']);
    }

    public function testFetchCustomer()
    {
        $request = $this->gateway->fetchCustomer(
            array(
                'apiKey'            => 'key',
                'customerReference' => 'cst_bSNBBJBzdG'
            )
        );

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchCustomerRequest', $request);
    }
}
