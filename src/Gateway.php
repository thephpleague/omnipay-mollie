<?php

namespace Omnipay\Mollie;

use Omnipay\Common\AbstractGateway;
use Omnipay\Mollie\Message\CompletePurchaseRequest;
use Omnipay\Mollie\Message\CreateCustomerRequest;
use Omnipay\Mollie\Message\FetchCustomerRequest;
use Omnipay\Mollie\Message\FetchIssuersRequest;
use Omnipay\Mollie\Message\FetchPaymentMethodsRequest;
use Omnipay\Mollie\Message\FetchTransactionRequest;
use Omnipay\Mollie\Message\PurchaseRequest;
use Omnipay\Mollie\Message\RefundRequest;
use Omnipay\Mollie\Message\UpdateCustomerRequest;

/**
 * Mollie (iDeal) Gateway
 *
 * @link https://www.mollie.com/en/developers
 */
class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Mollie';
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
     * @return FetchIssuersRequest
     */
    public function fetchIssuers(array $parameters = [])
    {
        return $this->createRequest(FetchIssuersRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return FetchPaymentMethodsRequest
     */
    public function fetchPaymentMethods(array $parameters = [])
    {
        return $this->createRequest(FetchPaymentMethodsRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return RefundRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return CreateCustomerRequest
     */
    public function createCustomer(array $parameters = [])
    {
        return $this->createRequest(CreateCustomerRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return UpdateCustomerRequest
     */
    public function updateCustomer(array $parameters = [])
    {
        return $this->createRequest(UpdateCustomerRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return FetchCustomerRequest
     */
    public function fetchCustomer(array $parameters = [])
    {
        return $this->createRequest(FetchCustomerRequest::class, $parameters);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
    }
}
