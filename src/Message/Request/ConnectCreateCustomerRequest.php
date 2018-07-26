<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Mollie\Message\Response\CreateCustomerResponse;

/**
 * Creates a simple minimal representation of a customer in the Mollie API.
 *
 * @see https://docs.mollie.com/reference/v2/customers-api/create-customer
 * @see https://docs.mollie.com/reference/v2/customers-api/create-customer#mollie-connect-oauth-parameters
 */
class ConnectCreateCustomerRequest extends CreateCustomerRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'description', 'email');

        $data                = [];
        $data['name']        = $this->getDescription();
        $data['email']       = $this->getEmail();
        $data['locale']      = $this->getLocale();

        if ($this->getMetadata()) {
            $data['metadata'] = $this->getMetadata();
        }

        if ($this->getTestMode()) {
            $data['testmode'] = $this->getTestMode();
        }

        return $data;
    }

    /**
     * @param array $data
     * @return CreateCustomerResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::POST, '/customers', $data);

        return $this->response = new CreateCustomerResponse($this, $response);
    }
}
