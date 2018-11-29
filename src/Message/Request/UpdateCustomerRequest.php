<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Mollie\Message\Response\UpdateCustomerResponse;

/**
 * Update an existing customer.
 *
 * @see https://docs.mollie.com/reference/v2/customers-api/update-customer
 * @method UpdateCustomerResponse send()
 */
class UpdateCustomerRequest extends AbstractMollieRequest
{
    /**
     * Get the customer's reference id.
     *
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
     * Get the customer's email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Get the customer's locale.
     *
     * Possible values: de_DE, en_US, es_ES, fr_FR, nl_BE, fr_BE, nl_NL.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * Optional value.
     *
     * @param string $value
     * @return AbstractRequest
     */
    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }

    /**
     * Get the customer's metadata.
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->getParameter('metadata');
    }

    /**
     * Optional value.
     *
     * @param array $value
     * @return AbstractRequest
     */
    public function setMetadata($value)
    {
        return $this->setParameter('metadata', $value);
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'customerReference');

        $data                = [];
        $data['name']        = $this->getDescription();
        $data['email']       = $this->getEmail();
        $data['locale']      = $this->getLocale();

        if ($this->getMetadata()) {
            $data['metadata'] = $this->getMetadata();
        }

        return $data;
    }

    /**
     * @param array $data
     * @return UpdateCustomerResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::POST, '/customers/' . $this->getCustomerReference(), $data);

        return $this->response = new UpdateCustomerResponse($this, $response);
    }
}
