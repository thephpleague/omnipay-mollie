<?php

namespace Omnipay\Mollie\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $endpoint = 'https://api.mollie.nl/v1';

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
        $httpRequest = $this->httpClient->createRequest(
            $method,
            $this->endpoint . $endpoint,
            array(
                'Authorization' => 'Bearer ' . $this->getApiKey()
            ),
            json_encode($data)
        );

        return $this->httpClient->sendRequest($httpRequest);
    }
}
