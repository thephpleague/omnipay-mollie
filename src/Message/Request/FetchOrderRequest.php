<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Response\FetchOrderResponse;

/**
 * Retrieve a single order object by its payment token.
 *
 * @see https://docs.mollie.com/reference/v2/payments-api/get-order
 * @method FetchOrderResponse send()
 */
class FetchOrderRequest extends AbstractMollieRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'transactionReference');

        $data = [];
        $data['id'] = $this->getTransactionReference();

        return $data;
    }

    /**
     * @return bool
     */
    public function hasIncludePayments()
    {
        return (bool) $this->getParameter('includePayments');
    }

    /**
     * @param array $data
     * @return FetchOrderResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            self::GET,
            \sprintf(
                '/orders/%s%s',
                $data['id'],
                $this->hasIncludePayments() ? '?embed=payments' : ''
            )
        );

        return $this->response = new FetchOrderResponse($this, $response);
    }

    /**
     * @param bool $includePayments
     * @return self
     */
    public function setIncludePayments($includePayments)
    {
        return $this->setParameter('includePayments', $includePayments);
    }
}
