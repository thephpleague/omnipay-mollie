<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Mollie\Message\Response\FetchCustomerResponse;

/**
 * Mollie Fetch Customer Request.
 *
 * URL: https://www.mollie.com/en/docs/reference/customers/get
 */
class FetchCustomerRequest extends AbstractMollieRequest
{
    /**
     * @return string
     */
    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setCustomerReference($value)
    {
        return $this->setParameter('customerReference', $value);
    }

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'customerReference');

        return [];
    }

    /**
     * @param array $data
     * @return FetchCustomerResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::GET, '/customers/' . $this->getCustomerReference(), $data);

        return $this->response = new FetchCustomerResponse($this, $response);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->baseUrl . '/customers';
    }
}
