<?php

namespace Omnipay\Mollie;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Mollie\Message\Request\CompletePurchaseRequest;
use Omnipay\Mollie\Message\Request\CreateCustomerRequest;
use Omnipay\Mollie\Message\Request\FetchCustomerRequest;
use Omnipay\Mollie\Message\Request\FetchIssuersRequest;
use Omnipay\Mollie\Message\Request\FetchPaymentMethodsRequest;
use Omnipay\Mollie\Message\Request\FetchTransactionRequest;
use Omnipay\Mollie\Message\Request\PurchaseRequest;
use Omnipay\Mollie\Message\Request\RefundRequest;
use Omnipay\Mollie\Message\Request\UpdateCustomerRequest;

/**
 * Mollie Gateway provides a wrapper for Mollie API.
 * Please have a look at links below to have a high-level overview and see the API specification
 *
 * @see https://www.mollie.com/en/developers
 * @see https://docs.mollie.com/index
 *
 * @method RequestInterface authorize(array $options = array())
 * @method RequestInterface completeAuthorize(array $options = array())
 * @method RequestInterface capture(array $options = array())
 * @method RequestInterface void(array $options = array())
 * @method RequestInterface createCard(array $options = array())
 * @method RequestInterface updateCard(array $options = array())
 * @method RequestInterface deleteCard(array $options = array())
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
     * @return AbstractRequest|FetchIssuersRequest
     */
    public function fetchIssuers(array $parameters = [])
    {
        return $this->createRequest(FetchIssuersRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return AbstractRequest|FetchPaymentMethodsRequest
     */
    public function fetchPaymentMethods(array $parameters = [])
    {
        return $this->createRequest(FetchPaymentMethodsRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return AbstractRequest|FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return AbstractRequest|PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return AbstractRequest|CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return AbstractRequest|RefundRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return AbstractRequest
     */
    public function createCustomer(array $parameters = [])
    {
        return $this->createRequest(CreateCustomerRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return AbstractRequest|UpdateCustomerRequest
     */
    public function updateCustomer(array $parameters = [])
    {
        return $this->createRequest(UpdateCustomerRequest::class, $parameters);
    }

    /**
     * @param  array $parameters
     * @return AbstractRequest|FetchCustomerRequest
     */
    public function fetchCustomer(array $parameters = [])
    {
        return $this->createRequest(FetchCustomerRequest::class, $parameters);
    }
}
