<?php


namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\RefundResponse;

/**
 * Most payment methods support refunds. This means you can request your payment to be refunded to the consumer. The
 * amount of the refund will be withheld from your next settlement.
 *
 * Refunds are not available at all for Bitcoin, paysafecard and gift cards. If you need to refund direct debit
 * payments, please contact our support department.
 *
 * Refunds support descriptions, which we will show in the Dashboard, your exports and pass to the consumer if possible.
 * If you have insufficient balance with Mollie to perform the refund, the refund will be queued. We will automatically
 * process the refund once your balance increases.
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
