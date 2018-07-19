<?php

namespace Omnipay\Mollie\Message;

/**
 * Mollie Fetch Issuers Request
 *
 * @method \Omnipay\Mollie\Message\FetchIssuersResponse send()
 */
class FetchIssuersRequest extends AbstractRequest
{

    protected $endpoint = '/methods/ideal?include=issuers';

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('apiKey');

        return [];
    }

    public function sendData($data)
    {
        $response = $this->sendRequest('GET', $this->endpoint);

        return $this->response = new FetchIssuersResponse($this, $response);
    }
}
