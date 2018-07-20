<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\FetchPaymentMethodsResponse;

/**
 * Mollie Fetch PaymentMethods Request
 *
 * @method FetchPaymentMethodsResponse send()
 */
class FetchPaymentMethodsRequest extends AbstractMollieRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey');

        return [];
    }

    /**
     * @param array $data
     * @return ResponseInterface|FetchPaymentMethodsResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::GET, '/methods');

        return $this->response = new FetchPaymentMethodsResponse($this, $response);
    }
}
