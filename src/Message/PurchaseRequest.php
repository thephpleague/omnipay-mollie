<?php
namespace Omnipay\Mollie\Message;

/**
 * Mollie Purchase Request
 *
 * @method \Omnipay\Mollie\Message\PurchaseResponse send()
 */
class PurchaseRequest extends AbstractRequest
{
    public function getMetadata()
    {
        return $this->getParameter('metadata');
    }

    public function setMetadata($value)
    {
        return $this->setParameter('metadata', $value);
    }

    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }

    public function getBillingEmail()
    {
        return $this->getParameter('billingEmail');
    }

    public function setBillingEmail($value)
    {
        return $this->setParameter('billingEmail', $value);
    }

    public function getData()
    {
        $this->validate('apiKey', 'amount', 'description', 'returnUrl');

        $data                = array();
        $data['amount']      = $this->getAmount();
        $data['description'] = $this->getDescription();
        $data['redirectUrl'] = $this->getReturnUrl();
        $data['method']      = $this->getPaymentMethod();
        $data['metadata']    = $this->getMetadata();

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

        return $data;
    }

    public function sendData($data)
    {
        $response = $this->sendRequest('POST', '/payments', $data);

        return $this->response = new PurchaseResponse($this, $response);
    }
}
