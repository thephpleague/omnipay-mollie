<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Mollie\Message\Response\CreateCustomerMandateResponse;

/**
 * Create a mandate for a specific customer.
 *
 * @see https://docs.mollie.com/reference/v2/mandates-api/create-mandate
 * @method CreateCustomerMandateResponse send()
 */
class CreateCustomerMandateRequest extends AbstractMollieRequest
{
    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->getParameter('method');
    }

    /**
     * @param string $method
     * @return AbstractRequest
     */
    public function setMethod($method)
    {
        return $this->setParameter('method', $method);
    }

    /**
     * @return string
     */
    public function getConsumerName()
    {
        return $this->getParameter('consumerName');
    }

    /**
     * @param string $consumerName
     * @return AbstractRequest
     */
    public function setConsumerName($consumerName)
    {
        return $this->setParameter('consumerName', $consumerName);
    }

    /**
     * @return string
     */
    public function getConsumerAccount()
    {
        return $this->getParameter('consumerAccount');
    }

    /**
     * @param string $consumerAccount
     * @return AbstractRequest
     */
    public function setConsumerAccount($consumerAccount)
    {
        return $this->setParameter('consumerAccount', $consumerAccount);
    }

    /**
     * @return string
     */
    public function getConsumerBic()
    {
        return $this->getParameter('consumerBic');
    }

    /**
     * @param string $consumerBic
     * @return AbstractRequest
     */
    public function setConsumerBic($consumerBic)
    {
        return $this->setParameter('consumerBic', $consumerBic);
    }

    /**
     * @return string
     */
    public function getSignatureDate()
    {
        return $this->getParameter('signatureDate');
    }

    /**
     * @param string $signatureDate
     * @return AbstractRequest
     */
    public function setSignatureDate($signatureDate)
    {
        return $this->setParameter('signatureDate', $signatureDate);
    }

    /**
     * @return string
     */
    public function getMandateReference()
    {
        return $this->getParameter('mandateReference');
    }

    /**
     * @param string $mandateReference
     * @return AbstractRequest
     */
    public function setMandateReference($mandateReference)
    {
        return $this->setParameter('mandateReference', $mandateReference);
    }

    /**
     * @return string
     */
    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    /**
     * @param string $customerReference
     * @return AbstractRequest
     */
    public function setCustomerReference($customerReference)
    {
        return $this->setParameter('customerReference', $customerReference);
    }


    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'customerReference', 'method', 'consumerName', 'consumerAccount');

        $data = array();
        $data['method'] = $this->getMethod();
        $data['consumerName'] = $this->getConsumerName();
        $data['consumerAccount'] = $this->getConsumerAccount();

        if ($this->getConsumerBic()) {
            $data['consumerBic'] = $this->getConsumerBic();
        }

        if ($this->getSignatureDate()) {
            $data['signatureDate'] = $this->getSignatureDate();
        }

        if ($this->getMandateReference()) {
            $data['mandateReference'] = $this->getMandateReference();
        }

        return $data;
    }

    /**
     * @param array $data
     * @return CreateCustomerMandateResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::POST, "/customers/{$this->getCustomerReference()}/mandates", $data);

        return $this->response = new CreateCustomerMandateResponse($this, $response);
    }
}
