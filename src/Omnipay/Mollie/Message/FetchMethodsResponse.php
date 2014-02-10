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

            foreach ($this->data['data'] as $method) {
                $result[] = array(
                    'id'     => $method['id'],
                    'name'   => $method['description']
                );
            }

            return $result;
        }

        return null;
    }
}