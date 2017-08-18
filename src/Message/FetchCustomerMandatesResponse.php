<?php
/**
 * Date: 18/08/17
 * Time: 12:44
 */

namespace Omnipay\Mollie\Message;


class FetchCustomerMandatesResponse extends AbstractResponse
{
    public function getMandates()
    {
        if(isset($this->data['data'])){
            return $this->data['data'];
        }
    }
}