<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\FetchTransactionResponse;

/**
 * Mollie Fetch Transaction Request
 *
 * @method \Omnipay\Mollie\Message\Response\FetchTransactionResponse send()
 */
class FetchTransactionRequest extends AbstractMollieRequest
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
     * @return ResponseInterface|FetchTransactionResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::GET, '/payments/' . $data['id']);

        return $this->response = new FetchTransactionResponse($this, $response);
    }
}
