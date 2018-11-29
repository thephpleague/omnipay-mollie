<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Mollie\Message\Response\FetchCustomerMandatesResponse;

/**
 * Retrieve all mandates for the given customer.
 *
 * @see https://docs.mollie.com/reference/v2/mandates-api/list-mandates
 * @method FetchCustomerMandatesResponse send()
 */
class FetchCustomerMandatesRequest extends AbstractMollieRequest
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
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'customerReference');

        return array();
    }

    /**
     * @param array $data
     * @return FetchCustomerMandatesResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::GET, "/customers/{$this->getCustomerReference()}/mandates", $data);

        return $this->response = new FetchCustomerMandatesResponse($this, $response);
    }
}
