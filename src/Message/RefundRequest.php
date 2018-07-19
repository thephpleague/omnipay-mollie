<?php


namespace Omnipay\Mollie\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

class RefundRequest extends AbstractRequest
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
        $response = $this->sendRequest('POST', '/payments/' . $this->getTransactionReference() . '/refunds', $data);

        return $this->response = new RefundResponse($this, $response);
    }
}
