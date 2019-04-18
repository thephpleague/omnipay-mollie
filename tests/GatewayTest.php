<?php

namespace Omnipay\Mollie\Test;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Gateway;
use Omnipay\Mollie\Message\Request\CancelOrderRequest;
use Omnipay\Mollie\Message\Request\CompletePurchaseRequest;
use Omnipay\Mollie\Message\Request\CreateCustomerMandateRequest;
use Omnipay\Mollie\Message\Request\CreateCustomerRequest;
use Omnipay\Mollie\Message\Request\FetchCustomerMandatesRequest;
use Omnipay\Mollie\Message\Request\FetchCustomerRequest;
use Omnipay\Mollie\Message\Request\FetchIssuersRequest;
use Omnipay\Mollie\Message\Request\FetchPaymentMethodsRequest;
use Omnipay\Mollie\Message\Request\FetchTransactionRequest;
use Omnipay\Mollie\Message\Request\PurchaseRequest;
use Omnipay\Mollie\Message\Request\RefundRequest;
use Omnipay\Mollie\Message\Request\RevokeCustomerMandateRequest;
use Omnipay\Mollie\Message\Request\UpdateCustomerRequest;
use Omnipay\Tests\GatewayTestCase;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
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

    /**
     * @throws InvalidRequestException
     */
    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00', 'currency' => 'EUR'));

        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
    }

    /**
     * @throws InvalidRequestException
     */
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
                'transactionReference' => 'tr_Qzin4iTWrU',
                'amount'               => '10.00',
                'currency'             => 'EUR'
            )
        );

        $this->assertInstanceOf(RefundRequest::class, $request);
        $data = $request->getData();
        $this->assertSame(
            [
                'value' => '10.00',
                'currency' => 'EUR'
            ],
            $data['amount']
        );
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testThatRefundDoesntWorkWithoutAmount()
    {
        $request = $this->gateway->refund(
            array(
                'apiKey'               => 'key',
                'transactionReference' => 'tr_Qzin4iTWrU'
            )
        );

        $this->assertInstanceOf(RefundRequest::class, $request);
        $request->getData();
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction(
            array(
                'apiKey' => 'key',
                'transactionReference' => 'tr_Qzin4iTWrU',
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
                'description' => 'Test name',
                'email' => 'test@example.com',
                'metadata' => 'Something something something dark side.',
                'locale' => 'nl_NL',
            )
        );

        $this->assertInstanceOf(CreateCustomerRequest::class, $request);
    }

    public function testUpdateCustomer()
    {
        $request = $this->gateway->updateCustomer(
            array(
                'apiKey' => 'key',
                'customerReference' => 'cst_bSNBBJBzdG',
                'description' => 'Test name2',
                'email' => 'test@example.com',
                'metadata' => 'Something something something dark side.',
                'locale' => 'nl_NL',
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
                'apiKey' => 'key',
                'customerReference' => 'cst_bSNBBJBzdG',
            )
        );

        $this->assertInstanceOf(FetchCustomerRequest::class, $request);
    }

    public function testFetchCustomerMandates()
    {
        $request = $this->gateway->fetchCustomerMandates(
            array(
                'apiKey' => 'key',
                'customerReference' => 'cst_bSNBBJBzdG',
            )
        );

        $this->assertInstanceOf(FetchCustomerMandatesRequest::class, $request);
    }

    public function testRevokeCustomerMandate()
    {
        $request = $this->gateway->revokeCustomerMandate(
            array(
                'apiKey' => "key",
                "customerReference" => "cst_bSNBBJBzdG",
                "mandateId" => "mdt_pWUnw6pkBN",
            )
        );

        $this->assertInstanceOf(RevokeCustomerMandateRequest::class, $request);
    }

    public function testCreateCustomerMandate()
    {
        $request = $this->gateway->createCustomerMandate(
            array(
                'apiKey' => "mykey",
                'consumerName' => "Customer A",
                'consumerAccount' => "NL53INGB0000000000",
                "method" => "directdebit",
                'customerReference' => 'cst_bSNBBJBzdG',
                'mandateReference' => "YOUR-COMPANY-MD13804",
            )
        );

        $this->assertInstanceOf(CreateCustomerMandateRequest::class, $request);
    }

    public function testVoid()
    {
        $this->assertInstanceOf(CancelOrderRequest::class, $this->gateway->void());
    }
}
