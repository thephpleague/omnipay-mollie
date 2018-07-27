<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Mollie\Message\Response\FetchCustomerResponse;

/**
 * Retrieve a single customer by its ID.
 *
 * @see https://docs.mollie.com/reference/v2/customers-api/get-customer
 * @see https://docs.mollie.com/reference/v2/customers-api/get-customer#mollie-connect-oauth-parameters
 */
class ConnectFetchCustomerRequest extends FetchCustomerRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'customerReference');

        $data = [];

        if ($this->getTestMode()) {
            $data['testmode'] = $this->getTestMode();
        }

        return $data;
    }

    /**
     * @param array $data
     * @return FetchCustomerResponse
     */
    public function sendData($data)
    {
        $queryString = isset($data['testmode']) ? '?testmode=' . var_export($data['testmode'], true) : '';

        $response = $this->sendRequest(self::GET, '/customers/' . $this->getCustomerReference() . $queryString);

        return $this->response = new FetchCustomerResponse($this, $response);
    }
}
