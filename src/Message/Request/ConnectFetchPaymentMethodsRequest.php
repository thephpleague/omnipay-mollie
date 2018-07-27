<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\FetchPaymentMethodsResponse;

/**
 * Retrieve all available payment methods.
 *
 * @see https://docs.mollie.com/reference/v2/methods-api/list-methods
 * @see https://docs.mollie.com/reference/v2/methods-api/list-methods#mollie-connect-oauth-parameters
 */
class ConnectFetchPaymentMethodsRequest extends FetchPaymentMethodsRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'profileId');

        $data = [];
        $data['profileId'] = $this->getProfileId();

        if ($this->getTestMode()) {
            $data['testmode'] = $this->getTestMode();
        }

        if (empty($data['profileId'])) {
            throw new InvalidRequestException("The profileId parameter is required");
        }

        return $data;
    }

    /**
     * @param array $data
     * @return ResponseInterface|FetchPaymentMethodsResponse
     */
    public function sendData($data)
    {
        $queryString = "?profileId=" . $data['profileId'];
        $queryString .= isset($data['testmode']) ? '&testmode=' . var_export($data['testmode'], true) : '';

        $response = $this->sendRequest(self::GET, '/methods' . $queryString);

        return $this->response = new FetchPaymentMethodsResponse($this, $response);
    }
}
