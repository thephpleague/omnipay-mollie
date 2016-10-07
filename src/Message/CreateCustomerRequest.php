<?php

/**
 * Mollie Create Customer Request.
 */
namespace Omnipay\Mollie\Message;

class CreateCustomerRequest extends AbstractRequest
{
    /**
     * Get the customer's email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }

    /**
     * @return string
     */
    public function getMetadata()
    {
        return $this->getParameter('metadata');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setMetadata($value)
    {
        return $this->setParameter('metadata', $value);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('apiKey', 'description', 'email');

        $data                = array();
        $data['name']        = $this->getDescription();
        $data['email']       = $this->getEmail();
        $data['metadata']    = $this->getMetadata();
        $data['locale']      = $this->getLocale();

        if ($this->getMetadata()) {
            $data['metadata'] = $this->getMetadata();
        }

        return $data;
    }

    /**
     * @param mixed $data
     * @return CreateCustomerResponse
     */
    public function sendData ($data)
    {
        $httpResponse = $this->sendRequest('POST', '/customers', $data);

        return $this->response = new CreateCustomerResponse($this, $httpResponse->json());
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint.'/customers';
    }
}