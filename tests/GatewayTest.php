<?php

namespace Omnipay\Mollie\Test;

use Omnipay\Mollie\Gateway;
use Omnipay\Mollie\Message\CompletePurchaseRequest;
use Omnipay\Mollie\Message\CreateCustomerRequest;
use Omnipay\Mollie\Message\FetchCustomerRequest;
use Omnipay\Mollie\Message\FetchIssuersRequest;
use Omnipay\Mollie\Message\FetchPaymentMethodsRequest;
use Omnipay\Mollie\Message\FetchTransactionRequest;
use Omnipay\Mollie\Message\PurchaseRequest;
use Omnipay\Mollie\Message\RefundRequest;
use Omnipay\Mollie\Message\UpdateCustomerRequest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway();
    }

    public function testFetchIssuers()
    {
        $request = $this->gateway->fetchIssuers();

        $this->assertInstanceOf(FetchIssuersRequest::class, $request);
    }

    public function testFetchPaymentMethods()
    {
        $request = $this->gateway->fetchPaymentMethods();

        $this->assertInstanceOf(FetchPaymentMethodsRequest::class, $request);
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00', 'currency' => 'EUR'));

        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
    }

    public function testPurchaseReturn()
    {
        $request = $this->gateway->completePurchase(array('amount' => '10.00', 'currency' => 'EUR'));

        $this->assertInstanceOf(CompletePurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
    }

    public function testRefund()
    {
        $request = $this->gateway->refund(
            array(
                'apiKey'               => 'key',
                'transactionReference' => 'tr_Qzin4iTWrU'
            )
        );

        $this->assertInstanceOf(RefundRequest::class, $request);
        $data = $request->getData();
        $this->assertFalse(array_key_exists('amount', $data));
        $request = $this->gateway->refund(
            array(
                'apiKey'               => 'key',
                'transactionReference' => 'tr_Qzin4iTWrU',
                'amount'               => '10.00',
                'currency'             => 'EUR'
            )
        );

        $this->assertInstanceOf(RefundRequest::class, $request);
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

        $this->assertInstanceOf(FetchTransactionRequest::class, $request);

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

        $this->assertInstanceOf(CreateCustomerRequest::class, $request);
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

        $this->assertInstanceOf(UpdateCustomerRequest::class, $request);

        $data = $request->getData();

        $this->assertSame('Test name2', $data['name']);
    }

    public function testFetchCustomer()
    {
        $request = $this->gateway->fetchCustomer(
            array(
                'apiKey'            => 'key',
                'customerReference' => 'cst_bSNBBJBzdG'
            )
        );

        $this->assertInstanceOf(FetchCustomerRequest::class, $request);
    }
}
