<?php

/**
 * Mollie Fetch Customer Request.
 *
 * URL: https://www.mollie.com/en/docs/reference/customers/get
 */
namespace Omnipay\Mollie\Message;

use Omnipay\Common\Http\Decoder;

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
        $httpResponse = $this->sendRequest('GET', '/customers/' . $this->getCustomerReference(), $data);

        return $this->response = new FetchCustomerResponse($this, Decoder::json($httpResponse));
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint.'/customers';
    }
}
