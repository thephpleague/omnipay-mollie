<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\FetchPaymentMethodsResponse;

/**
 * Retrieve all available payment methods.
 *
 * @see https://docs.mollie.com/reference/v2/methods-api/list-methods
 * @method FetchPaymentMethodsResponse send()
 */
class FetchPaymentMethodsRequest extends AbstractMollieRequest
{
    /**
     * @param string $billingCountry
     * @return $this
     */
    public function setBillingCountry($billingCountry)
    {
        return $this->setParameter('billingCountry', $billingCountry);
    }

    /**
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->getParameter('billingCountry');
    }

    /**
     * @param string $includeWallets
     * @return $this
     */
    public function setIncludeWallets($includeWallets)
    {
        return $this->setParameter('includeWallets', $includeWallets);
    }

    /**
     * @return string
     */
    public function getIncludeWallets()
    {
        return $this->getParameter('includeWallets');
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        return $this->setParameter('locale', $locale);
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * @param string $resource
     * @return $this
     */
    public function setResource($resource)
    {
        return $this->setParameter('resource', $resource);
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return $this->getParameter('resource');
    }

    /**
     * @param $sequenceType
     * @return $this
     */
    public function setSequenceType($sequenceType)
    {
        return $this->setParameter('sequenceType', $sequenceType);
    }

    /**
     * @return string
     */
    public function getSequenceType()
    {
        return $this->getParameter('sequenceType');
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey');

        // Currency and amount are optional but both required when either one is supplied
        $amount = null;
        if ($this->getAmount() || $this->getCurrency()) {
            $this->validate('amount', 'currency');

            $amount = [
                'value' => $this->getAmount(),
                'currency' => $this->getCurrency(),
            ];
        }

        return [
            'amount' => $amount,
            'billingCountry' => $this->getBillingCountry(),
            'locale' => $this->getLocale(),
            'resource' => $this->getResource(),
            'includeWallets' => $this->getIncludeWallets(),
            'sequenceType' => $this->getSequenceType(),
        ];
    }

    /**
     * @param array $data
     * @return ResponseInterface|FetchPaymentMethodsResponse
     */
    public function sendData($data)
    {
        $query = http_build_query($data);
        $response = $this->sendRequest(self::GET, '/methods' . ($query ? '?' . $query : ''));

        return $this->response = new FetchPaymentMethodsResponse($this, $response);
    }
}
