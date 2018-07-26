<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\PurchaseResponse;

/**
 * Create a payment with the Mollie API.
 *
 * @see https://docs.mollie.com/reference/v2/payments-api/create-payment
 * @see https://docs.mollie.com/reference/v2/payments-api/create-payment#mollie-connect-oauth-parameters
 */
class ConnectPurchaseRequest extends PurchaseRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'amount', 'currency', 'description', 'returnUrl', 'profileId');

        $data = [];
        $data['amount'] = [
            "value" => $this->getAmount(),
            "currency" => $this->getCurrency()
        ];
        $data['description'] = $this->getDescription();
        $data['redirectUrl'] = $this->getReturnUrl();
        $data['method'] = $this->getPaymentMethod();
        $data['metadata'] = $this->getMetadata();
        $data['profileId'] = $this->getProfileId();

        if ($this->getTestMode()) {
            $data['testmode'] = $this->getTestMode();
        }

        if ($this->getTransactionId()) {
            $data['metadata']['transactionId'] = $this->getTransactionId();
        }

        if ($issuer = $this->getIssuer()) {
            $data['issuer'] = $issuer;
        }

        $webhookUrl = $this->getNotifyUrl();
        if (null !== $webhookUrl) {
            $data['webhookUrl'] = $webhookUrl;
        }

        if ($locale = $this->getLocale()) {
            $data['locale'] = $locale;
        }

        if ($billingEmail = $this->getBillingEmail()) {
            $data['billingEmail'] = $billingEmail;
        }

        if (empty($data['profileId'])) {
            throw new InvalidRequestException("The profileId parameter is required");
        }

        return $data;
    }
}
