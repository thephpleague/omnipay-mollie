<?php

namespace Omnipay\Mollie\Message;

class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    public function isSuccessful()
    {
        if(isset($this->data['status']) && isset($this->data['detail'])) {
            return $this->data['status'] >= 200 && $this->data['status'] < 300;
        }

        return true;
    }

    public function getMessage()
    {
        if (isset($this->data['title']) && isset($this->data['detail'])) {
            return json_encode($this->data);
        }
    }
}
