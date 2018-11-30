<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Mollie\Message\Response\RevokeCustomerMandateResponse;

/**
 * Revoke a customer's mandate.
 *
 * @see https://docs.mollie.com/reference/v2/mandates-api/revoke-mandate
 * @method RevokeCustomerMandateResponse send()
 */
class RevokeCustomerMandateRequest extends AbstractMollieRequest
{
    /**
     * @return string
     */
    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    /**
     * @param string $value
     * @return AbstractRequest
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
     * @param string $value
     * @return AbstractRequest
     */
    public function setMandateId($value)
    {
        return $this->setParameter('mandateId', $value);
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'customerReference', 'mandateId');

        $data['customerReference'] = $this->getCustomerReference();
        $data['mandateId'] = $this->getMandateId();

        return $data;
    }
    
    /**
     * @param array $data
     * @return RevokeCustomerMandateResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            self::DELETE,
            "/customers/{$this->getCustomerReference()}/mandates/{$this->getMandateId()}"
        );

        return $this->response = new RevokeCustomerMandateResponse($this, $response);
    }
}
