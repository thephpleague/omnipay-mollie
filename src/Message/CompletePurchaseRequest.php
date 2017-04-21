<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\ResponseParser;

/**
 * Mollie Complete Purchase Request
 *
 * @method \Omnipay\Mollie\Message\CompletePurchaseResponse send()
 */
class CompletePurchaseRequest extends FetchTransactionRequest
{
    public function getData()
    {
        $this->validate('apiKey', 'transactionReference');

        $data = array();
        $data['id'] = $this->getTransactionReference();

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('GET', '/payments/' . $data['id']);

        return $this->response = new CompletePurchaseResponse($this, ResponseParser::json($httpResponse));
    }
}
