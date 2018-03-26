<?php

/**
 * Mollie Create Customer Request.
 *
 * URL: https://www.mollie.com/en/docs/reference/customers/create
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
     * Get the customer's locale.
     *
     * Possible values: de_DE, en_US, es_ES, fr_FR, nl_BE, fr_BE, nl_NL.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * Optional value.
     *
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }

    /**
     * Get the customer's metadata.
     *
     * @return string
     */
    public function getMetadata()
    {
        return $this->getParameter('metadata');
    }

    /**
     * Optional value.
     *
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
    public function sendData($data)
    {
        $response = $this->sendRequest('POST', '/customers', $data);

        return $this->response = new CreateCustomerResponse($this, $response);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint.'/customers';
    }
}
