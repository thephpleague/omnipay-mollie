<?php

namespace Omnipay\Mollie;

use Omnipay\Common\AbstractGateway;

/**
 * Mollie (iDeal) Gateway
 *
 * @link https://www.mollie.nl/files/documentatie/payments-api.html
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
     * @return \Omnipay\Mollie\Message\FetchIssuersRequest
     */
    public function fetchIssuers(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\FetchIssuersRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Mollie\Message\FetchPaymentMethodsRequest
     */
    public function fetchPaymentMethods(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\FetchPaymentMethodsRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Mollie\Message\FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\FetchTransactionRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Mollie\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Mollie\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Mollie\Message\RefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\RefundRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Mollie\Message\CreateCustomerRequest
     */
    public function createCustomer(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\CreateCustomerRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Mollie\Message\UpdateCustomerRequest
     */
    public function updateCustomer(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\UpdateCustomerRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Mollie\Message\FetchCustomerRequest
     */
    public function fetchCustomer(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\FetchCustomerRequest', $parameters);
    }
}
