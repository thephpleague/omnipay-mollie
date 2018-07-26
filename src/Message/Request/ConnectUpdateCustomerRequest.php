<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Mollie\Message\Response\UpdateCustomerResponse;

/**
 * Update an existing customer.
 *
 * @see https://docs.mollie.com/reference/v2/customers-api/update-customer
 * @see https://docs.mollie.com/reference/v2/customers-api/update-customer#mollie-connect-oauth-parameters
 */
class ConnectUpdateCustomerRequest extends UpdateCustomerRequest
{
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

        if ($this->getTestMode()) {
            $data['testmode'] = $this->getTestMode();
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
