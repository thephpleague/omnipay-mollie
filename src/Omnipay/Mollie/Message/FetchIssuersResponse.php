<?php

namespace Omnipay\Mollie\Message;

class FetchIssuersResponse extends AbstractResponse
{
    /**
     * Return available issuers as an associative array.
     *
     * @return array|null
     */
    public function getIssuers()
    {
        if (isset($this->data['data'])) {
            $result = array();

            foreach ($this->data['data'] as $issuer) {
                $result[] = array(
                    'id'     => $issuer['id'],
                    'name'   => $issuer['name'],
                    'method' => $issuer['method']
                );
            }

            return $result;
        }

        return null;
    }
}
