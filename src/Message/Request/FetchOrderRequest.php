<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\FetchOrderResponse;
use Omnipay\Mollie\Message\Response\FetchTransactionResponse;

/**
 * Retrieve a single order object by its payment token.
 *
 * @see https://docs.mollie.com/reference/v2/payments-api/get-order
 * @method FetchOrderResponse send()
 */
class FetchOrderRequest extends AbstractMollieRequest
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

        return $data;
    }

    /**
     * @param array $data
     * @return FetchOrderResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::GET, '/orders/' . $data['id']);

        return $this->response = new FetchOrderResponse($this, $response);
    }
}
