<?php

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/mandates-api/list-mandates
 */
class FetchCustomerMandatesResponse extends AbstractMollieResponse
{
    public function getMandates()
    {
        if (isset($this->data['_embedded']['mandates'])) {
            return $this->data['_embedded']['mandates'];
        }
    }

    public function hasValidMandates()
    {
        if ($mandates = $this->getMandates()) {
            foreach ($mandates as $mandate) {
                if ($mandate['status'] == "valid") {
                    return true;
                }
            }
        }

        return false;
    }
}
