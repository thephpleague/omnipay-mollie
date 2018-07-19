<?php

namespace Omnipay\Mollie\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $apiVersion = "v2";

    protected $baseUrl = 'https://api.mollie.com/';

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    protected function sendRequest($method, $endpoint, $data = null)
    {
        $response = $this->httpClient->request(
            $method,
            $this->baseUrl . $this->apiVersion . $endpoint,
            array(
                'Authorization' => 'Bearer ' . $this->getApiKey()
            ),
            json_encode($data)
        );

        return json_decode($response->getBody(), true);
    }
}
