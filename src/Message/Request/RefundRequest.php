<?php


namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\RefundResponse;

/**
 * Most payment methods support refunds. This means you can request your payment to be refunded to the consumer.
 * The amount of the refund will be withheld from your next settlement.
 *
 * @see https://docs.mollie.com/reference/v2/refunds-api/create-refund
 */
class RefundRequest extends AbstractMollieRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'transactionReference');

        $data = [];
        if ($this->getAmountInteger() > 0) {
            $data['amount'] = $this->getAmount();
        }

        return $data;
    }

    /**
     * @param array $data
     * @return ResponseInterface|RefundResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            self::POST,
            '/payments/' . $this->getTransactionReference() . '/refunds',
            $data
        );

        return $this->response = new RefundResponse($this, $response);
    }
}
