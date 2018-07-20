<?php


namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\RefundResponse;

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
        $response = $this->sendRequest(self::POST, '/payments/' . $this->getTransactionReference() . '/refunds', $data);

        return $this->response = new RefundResponse($this, $response);
    }
}
