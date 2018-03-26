<?php

/**
 * Mollie Fetch Customer Request.
 *
 * URL: https://www.mollie.com/en/docs/reference/customers/get
 */
namespace Omnipay\Mollie\Message;

class FetchCustomerRequest extends AbstractRequest
{
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

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('apiKey', 'customerReference');

        return array();
    }

    /**
     * @param mixed $data
     * @return FetchCustomerResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest('GET', '/customers/' . $this->getCustomerReference(), $data);

        return $this->response = new FetchCustomerResponse($this, $response);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint.'/customers';
    }
}
