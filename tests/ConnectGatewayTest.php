<?php

namespace Omnipay\Mollie\Test;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\ConnectGateway;
use Omnipay\Mollie\Message\Request\ConnectCompletePurchaseRequest;
use Omnipay\Mollie\Message\Request\ConnectCreateCustomerRequest;
use Omnipay\Mollie\Message\Request\ConnectFetchCustomerRequest;
use Omnipay\Mollie\Message\Request\ConnectFetchIssuersRequest;
use Omnipay\Mollie\Message\Request\ConnectFetchPaymentMethodsRequest;
use Omnipay\Mollie\Message\Request\ConnectFetchTransactionRequest;
use Omnipay\Mollie\Message\Request\ConnectPurchaseRequest;
use Omnipay\Mollie\Message\Request\ConnectRefundRequest;
use Omnipay\Mollie\Message\Request\ConnectUpdateCustomerRequest;
use Omnipay\Tests\GatewayTestCase;

class ConnectGatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ConnectGateway();
    }

    public function testFetchIssuers()
    {
        $request = $this->gateway->fetchIssuers(array('profileId' => 'pfl_3RkSN1zuPE', 'testmode' => true));

        $this->assertInstanceOf(ConnectFetchIssuersRequest::class, $request);
        $this->assertSame('pfl_3RkSN1zuPE', $request->getProfileId());
        $this->assertSame(true, $request->getTestMode());
    }

    public function testFetchPaymentMethods()
    {
        $request = $this->gateway->fetchPaymentMethods(array('profileId' => 'pfl_3RkSN1zuPE', 'testmode' => true));

        $this->assertInstanceOf(ConnectFetchPaymentMethodsRequest::class, $request);
        $this->assertSame('pfl_3RkSN1zuPE', $request->getProfileId());
        $this->assertSame(true, $request->getTestMode());
    }

    /**
     * @throws InvalidRequestException
     */
    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00', 'currency' => 'EUR', 'profileId' => 'pfl_3RkSN1zuPE', 'testmode' => true));

        $this->assertInstanceOf(ConnectPurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
        $this->assertSame('pfl_3RkSN1zuPE', $request->getProfileId());
        $this->assertSame(true, $request->getTestMode());
    }

    /**
     * @throws InvalidRequestException
     */
    public function testPurchaseReturn()
    {
        $request = $this->gateway->completePurchase(array('amount' => '10.00', 'currency' => 'EUR', 'testmode' => true));

        $this->assertInstanceOf(ConnectCompletePurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
        $this->assertSame(true, $request->getTestMode());
    }

    public function testRefund()
    {
        $request = $this->gateway->refund(
            array(
                'apiKey'               => 'key',
                'transactionReference' => 'tr_Qzin4iTWrU',
                'amount'               => '10.00',
                'currency'             => 'EUR',
                'testmode'             => true,
            )
        );

        $this->assertInstanceOf(ConnectRefundRequest::class, $request);
        $data = $request->getData();
        $this->assertSame(
            [
                'value' => '10.00',
                'currency' => 'EUR'
            ],
            $data['amount']
        );
        $this->assertSame(true, $data['testmode']);
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testThatRefundDoesntWorkWithoutAmount()
    {
        $request = $this->gateway->refund(
            array(
                'apiKey'               => 'key',
                'transactionReference' => 'tr_Qzin4iTWrU',
                'testmode'             => true,
            )
        );

        $this->assertInstanceOf(ConnectRefundRequest::class, $request);
        $request->getData();
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction(
            array(
                'apiKey'               => 'key',
                'transactionReference' => 'tr_Qzin4iTWrU',
                'testmode'             => true
            )
        );

        $this->assertInstanceOf(ConnectFetchTransactionRequest::class, $request);

        $data = $request->getData();
        $this->assertSame('tr_Qzin4iTWrU', $data['id']);
        $this->assertSame('true', $data['testmode']);
    }

    public function testCreateCustomer()
    {
        $request = $this->gateway->createCustomer(
            array(
                'description'  => 'Test name',
                'email'        => 'test@example.com',
                'metadata'     => 'Something something something dark side.',
                'locale'       => 'nl_NL',
                'testmode'     => true,
            )
        );

        $data = $request->getData();

        $this->assertInstanceOf(ConnectCreateCustomerRequest::class, $request);
        $this->assertSame(true, $data['testmode']);
    }

    public function testUpdateCustomer()
    {
        $request = $this->gateway->updateCustomer(
            array(
                'customerReference' => 'cst_bSNBBJBzdG',
                'description'       => 'Test name2',
                'email'             => 'test@example.com',
                'metadata'          => 'Something something something dark side.',
                'locale'            => 'nl_NL',
                'testmode'          => true,
            )
        );

        $this->assertInstanceOf(ConnectUpdateCustomerRequest::class, $request);

        $data = $request->getData();

        $this->assertSame('Test name2', $data['name']);
        $this->assertSame(true, $data['testmode']);
    }

    public function testFetchCustomer()
    {
        $request = $this->gateway->fetchCustomer(
            array(
                'apiKey'            => 'key',
                'customerReference' => 'cst_bSNBBJBzdG',
                'testmode'          => true,
            )
        );

        $data = $request->getData();

        $this->assertInstanceOf(ConnectFetchCustomerRequest::class, $request);

        $this->assertSame('true', $data['testmode']);
    }
}
