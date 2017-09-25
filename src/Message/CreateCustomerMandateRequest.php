<?php
/**
 * Date: 21/08/17
 * Time: 13:07
 */

namespace Omnipay\Mollie\Message;


class CreateCustomerMandateRequest extends AbstractRequest
{
    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->getParameter('method');
    }

    /**
     * @param mixed $method
     * @return CreateCustomerMandateRequest
     */
    public function setMethod($method)
    {
        $this->setParameter('method', $method);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConsumerName()
    {
        return $this->getParameter('consumerName');
    }

    /**
     * @param mixed $consumerName
     * @return CreateCustomerMandateRequest
     */
    public function setConsumerName($consumerName)
    {
        $this->setParameter('consumerName', $consumerName);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConsumerAccount()
    {
        return $this->getParameter('consumerAccount');
    }

    /**
     * @param mixed $consumerAccount
     * @return CreateCustomerMandateRequest
     */
    public function setConsumerAccount($consumerAccount)
    {
        $this->setParameter('consumerAccount', $consumerAccount);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConsumerBic()
    {
        return $this->getParameter('consumerBic');
    }

    /**
     * @param mixed $consumerBic
     * @return CreateCustomerMandateRequest
     */
    public function setConsumerBic($consumerBic)
    {
        $this->setParameter('consumerBic', $consumerBic);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSignatureDate()
    {
        return $this->getParameter('signatureDate');
    }

    /**
     * @param mixed $signatureDate
     * @return CreateCustomerMandateRequest
     */
    public function setSignatureDate($signatureDate)
    {
        $this->setParameter('signatureDate', $signatureDate);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMandateReference()
    {
        return $this->getParameter('mandateReference');
    }

    /**
     * @param mixed $mandateReference
     * @return CreateCustomerMandateRequest
     */
    public function setMandateReference($value)
    {
        $this->setParameter('mandateReference', $value);

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setCustomerReference($value)
    {
        return $this->setParameter('customerReference', $value);
    }

    public function getData()
    {
        $this->validate('apiKey', 'customerReference', 'method', 'consumerName', 'consumerAccount');

        $data = array();
        $data['method'] = $this->getMethod();
        $data['consumerName'] = $this->getConsumerName();
        $data['consumerAccount'] = $this->getConsumerAccount();
        $data['customerReference'] = $this->getCustomerReference();

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
     * @param mixed $data
     * @return CreateCustomerMandateResponse
     */
    public function sendData($data)
    {
        $httpResponse = $this->sendRequest("POST", "/customers/{$this->getCustomerReference()}/mandates", $data);

        return $this->response = new CreateCustomerMandateResponse($this, $httpResponse->json());
    }
}
