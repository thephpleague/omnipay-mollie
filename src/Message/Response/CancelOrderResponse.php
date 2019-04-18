<?php

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/orders-api/cancel-order
 */
final class CancelOrderResponse extends FetchOrderResponse
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return 'canceled' === $this->data['status'] ?? '';
    }
}
