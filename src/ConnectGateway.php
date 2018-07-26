<?php

namespace Omnipay\Mollie;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Mollie\Message\Request\ConnectCompletePurchaseRequest;
use Omnipay\Mollie\Message\Request\ConnectCreateCustomerRequest;
use Omnipay\Mollie\Message\Request\ConnectFetchCustomerRequest;
use Omnipay\Mollie\Message\Request\ConnectFetchIssuersRequest;
use Omnipay\Mollie\Message\Request\ConnectFetchPaymentMethodsRequest;
use Omnipay\Mollie\Message\Request\ConnectFetchTransactionRequest;
use Omnipay\Mollie\Message\Request\ConnectPurchaseRequest;
use Omnipay\Mollie\Message\Request\ConnectRefundRequest;
use Omnipay\Mollie\Message\Request\ConnectUpdateCustomerRequest;

class ConnectGateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Mollie Connect';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'apiKey' => ''
        );
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * @param  string $value
     * @return $this
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * @param  array $parameters
     * @return ConnectFetchIssuersRequest
     */
    public function fetchIssuers(array $parameters = [])
    {
        /** @var ConnectFetchIssuersRequest $request */
        $request = $this->createRequest(ConnectFetchIssuersRequest::class, $parameters);

        return $request;
    }

    /**
     * @param  array $parameters
     * @return ConnectFetchPaymentMethodsRequest
     */
    public function fetchPaymentMethods(array $parameters = [])
    {
        /** @var ConnectFetchPaymentMethodsRequest $request */
        $request = $this->createRequest(ConnectFetchPaymentMethodsRequest::class, $parameters);

        return $request;
    }

    /**
     * @param  array $parameters
     * @return ConnectFetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = [])
    {
        /** @var ConnectFetchTransactionRequest $request */
        $request = $this->createRequest(ConnectFetchTransactionRequest::class, $parameters);

        return $request;
    }

    /**
     * @param  array $parameters
     * @return ConnectPurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        /** @var ConnectPurchaseRequest $request */
        $request = $this->createRequest(ConnectPurchaseRequest::class, $parameters);

        return $request;
    }

    /**
     * @param  array $parameters
     * @return CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        /** @var ConnectCompletePurchaseRequest $request */
        $request = $this->createRequest(ConnectCompletePurchaseRequest::class, $parameters);

        return $request;
    }

    /**
     * @param  array $parameters
     * @return ConnectRefundRequest
     */
    public function refund(array $parameters = [])
    {
        /** @var ConnectRefundRequest $request */
        $request = $this->createRequest(ConnectRefundRequest::class, $parameters);

        return $request;
    }

    /**
     * @param  array $parameters
     * @return ConnectCreateCustomerRequest
     */
    public function createCustomer(array $parameters = [])
    {
        /** @var ConnectCreateCustomerRequest $request */
        $request = $this->createRequest(ConnectCreateCustomerRequest::class, $parameters);

        return $request;
    }

    /**
     * @param  array $parameters
     * @return ConnectUpdateCustomerRequest
     */
    public function updateCustomer(array $parameters = [])
    {
        /** @var ConnectUpdateCustomerRequest $request */
        $request = $this->createRequest(ConnectUpdateCustomerRequest::class, $parameters);

        return $request;
    }

    /**
     * @param  array $parameters
     * @return ConnectFetchCustomerRequest
     */
    public function fetchCustomer(array $parameters = [])
    {
        /** @var ConnectFetchCustomerRequest $request */
        $request = $this->createRequest(ConnectFetchCustomerRequest::class, $parameters);

        return $request;
    }
}
