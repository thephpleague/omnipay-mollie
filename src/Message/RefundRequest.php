<?php


namespace Omnipay\Mollie\Message;

use Omnipay\Common\Http\ResponseParser;

class RefundRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('apiKey', 'transactionReference');

        $data = array();
        if ($this->getAmountInteger() > 0) {
            $data['amount'] = $this->getAmount();
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('POST', '/payments/' . $this->getTransactionReference() . '/refunds', $data);

        return $this->response = new RefundResponse($this, ResponseParser::json($httpResponse));
    }
}
