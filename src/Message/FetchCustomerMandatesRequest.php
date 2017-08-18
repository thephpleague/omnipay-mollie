<?php
/**
 * Date: 18/08/17
 * Time: 12:41
 */

namespace Omnipay\Mollie\Message;


use Omnipay\Common\Http\ResponseParser;

class FetchCustomerMandatesRequest extends AbstractRequest
{
    /**
     * @return string
     */
    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setCustomerReference($value)
    {
        return $this->setParameter('customerReference', $value);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('apiKey', 'customerReference');

        return array();
    }

    /**
     * @param mixed $data
     * @return FetchCustomerMandatesResponse
     */
    public function sendData($data)
    {
        $httpResponse = $this->sendRequest("GET", "/customers/{$this->getCustomerReference()}/mandates", $data);

        return $this->response = new FetchCustomerMandatesResponse($this, $httpResponse->json());
    }

}