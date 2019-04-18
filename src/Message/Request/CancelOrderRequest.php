<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Mollie\Message\Response\CancelOrderResponse;

/**
 * Cancel an order with the Mollie API.
 *
 * @see https://docs.mollie.com/reference/v2/orders-api/cancel-order
 * @method CancelOrderResponse send()
 */
final class CancelOrderRequest extends AbstractMollieRequest
{
    /**
     * @inheritdoc
     */
    public function getData()
    {
        $this->validate('apiKey', 'transactionReference');

        return [];
    }

    /**
     * @inheritdoc
     */
    public function sendData($data)
    {
        return $this->response = new CancelOrderResponse(
            $this,
            $this->sendRequest(self::DELETE, '/orders/'.$this->getTransactionReference(), $data)
        );
    }
}
