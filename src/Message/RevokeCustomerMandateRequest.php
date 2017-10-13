<?php
/**
 * Date: 21/08/17
 * Time: 12:00
 */

namespace Omnipay\Mollie\Message;


class RevokeCustomerMandateRequest extends AbstractRequest
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
     * @return string
     */
    public function getMandateId()
    {
        return $this->getParameter('mandateId');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setMandateId($value)
    {
        return $this->setParameter('mandateId', $value);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('apiKey', 'customerReference', "mandateId");

        $data['customerReference'] = $this->getCustomerReference();
        $data['mandateId'] = $this->getMandateId();

        return $data;
    }

    /**
     * @param mixed $data
     * @return RevokeCustomerMandateResponse
     */
    public function sendData($data)
    {
        $httpResponse = $this->sendRequest(
            "DELETE",
            "/customers/{$this->getCustomerReference()}/mandates/{$this->getMandateId()}"
        );

        return $this->response = new RevokeCustomerMandateResponse($this, $httpResponse->json());
    }
}
