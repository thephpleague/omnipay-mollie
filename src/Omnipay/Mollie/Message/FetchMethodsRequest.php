<?php

namespace Omnipay\Mollie\Message;

/**
 * Mollie Fetch Methods Request
 *
 * @method \Omnipay\Mollie\Message\FetchMethodsResponse send()
 */
class FetchMethodsRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('apiKey');

        $data = array();

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('GET', '/methods');

        return $this->response = new FetchMethodsResponse($this, $httpResponse->json());
    }
}