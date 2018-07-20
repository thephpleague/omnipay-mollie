<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\FetchPaymentMethodsResponse;

/**
 * Retrieve all available payment methods. The results are not paginated.
 *
 * For test mode, payment methods are returned that are enabled in the Dashboard (or the activation is pending).
 * For live mode, payment methods are returned that have been activated on your account and have been enabled in the
 * Dashboard.
 * When using the first sequence type, methods will be returned if they can be used as a first payment in a recurring
 * sequence and if they are enabled in the Dashboard.
 *
 * When using the recurring sequence type, methods that can be used for recurring payments or subscriptions will be
 * returned. Enabling / disabling methods in the dashboard does not affect how they can be used for recurring payments.
 *
 * @see https://docs.mollie.com/reference/v2/methods-api/list-methods
 *
 * @method FetchPaymentMethodsResponse send()
 */
class FetchPaymentMethodsRequest extends AbstractMollieRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey');

        return [];
    }

    /**
     * @param array $data
     * @return ResponseInterface|FetchPaymentMethodsResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::GET, '/methods');

        return $this->response = new FetchPaymentMethodsResponse($this, $response);
    }
}
