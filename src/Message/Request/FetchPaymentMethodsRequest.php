<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\FetchPaymentMethodsResponse;

/**
 * Retrieve all available payment methods.
 *
 * @see https://docs.mollie.com/reference/v2/methods-api/list-methods
 */
class FetchPaymentMethodsRequest extends AbstractMollieRequest
{
    /**
     * @param string $resource
     * @return $this
     */
    public function setResource(string $resource)
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
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey');

        return [
            'resource' => $this->getResource(),
        ];
    }

    /**
     * @param array $data
     * @return ResponseInterface|FetchPaymentMethodsResponse
     */
    public function sendData($data)
    {
        $query = http_build_query($data);
        $response = $this->sendRequest(self::GET, sprintf(
            '/methods%s',
            ($query ? '?' . $query : '')
        ));

        return $this->response = new FetchPaymentMethodsResponse($this, $response);
    }
}
