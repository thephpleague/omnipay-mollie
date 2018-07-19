<?php

namespace Omnipay\Mollie\Message;

class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    public function isSuccessful()
    {
        return !isset($this->data['status']) || ($this->data['status'] >= 200 && $this->data['status'] < 300);
    }

    public function getMessage()
    {
        if (isset($this->data['title']) && isset($this->data['detail'])) {
            return json_encode($this->data);
        }

        return "Request is successful.";
    }
}
