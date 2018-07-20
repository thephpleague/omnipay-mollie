<?php

namespace Omnipay\Mollie\Message\Response;

class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        if (isset($this->data['status']) && isset($this->data['detail'])) {
            return $this->data['status'] >= 200 && $this->data['status'] < 300;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return json_encode($this->data);
    }
}
