<?php

namespace Omnipay\Mollie\Message\Response;

use Omnipay\Common\Issuer;
use Omnipay\Common\Issuer as CommonIssuer;
use Omnipay\Common\Message\FetchIssuersResponseInterface;

class FetchIssuersResponse extends AbstractResponse implements FetchIssuersResponseInterface
{
    /**
     * Return available issuers as an associative array.
     *
     * @return CommonIssuer[]
     */
    public function getIssuers()
    {
        if (isset($this->data['issuers'])) {
            $issuers = [];
            foreach ($this->data['issuers'] as $issuer) {
                $issuers[] = new Issuer($issuer['id'], $issuer['name'], $this->data['id']);
            }

            return $issuers;
        }

        return [];
    }
}
