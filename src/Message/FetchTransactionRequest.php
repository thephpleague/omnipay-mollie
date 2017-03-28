<?php

namespace Omnipay\Mollie\Message;
use Omnipay\Common\Http\Decoder;

/**
 * Mollie Fetch Transaction Request
 *
 * @method \Omnipay\Mollie\Message\FetchTransactionResponse send()
 */
class FetchTransactionRequest extends AbstractRequest
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

        return $this->response = new FetchTransactionResponse($this, Decoder::json($httpResponse));
    }
}
