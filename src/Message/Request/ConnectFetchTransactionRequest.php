<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\FetchTransactionResponse;

/**
 * Retrieve a single payment object by its payment token.
 *
 * @see https://docs.mollie.com/reference/v2/payments-api/get-payment
 * @see https://docs.mollie.com/reference/v2/payments-api/get-payment#mollie-connect-oauth-parameters
 */
class ConnectFetchTransactionRequest extends FetchTransactionRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'transactionReference');

        $data = [];
        $data['id'] = $this->getTransactionReference();

        if ($this->getTestMode()) {
           $data['testmode'] = var_export($this->getTestMode(), true);
        }

        return $data;
    }

    /**
     * @param array $data
     * @return ResponseInterface|FetchTransactionResponse
     */
    public function sendData($data)
    {
        $queryString = isset($data['testmode']) ? '?testmode='.$data['testmode'] : '';

        $response = $this->sendRequest(self::GET, '/payments/' . $data['id'] . $queryString);

        return $this->response = new FetchTransactionResponse($this, $response);
    }
}
