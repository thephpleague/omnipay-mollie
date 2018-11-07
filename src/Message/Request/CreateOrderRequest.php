<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ItemBag;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Item;
use Omnipay\Mollie\Message\Response\CreateOrderResponse;
use Omnipay\Mollie\Message\Response\PurchaseResponse;

/**
 * Create an order with the Mollie API.
 *
 * @see https://docs.mollie.com/reference/v2/orders-api/create-order
 * @method CreateOrderResponse send()
 */
class CreateOrderRequest extends AbstractMollieRequest
{
    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->getParameter('metadata');
    }

    /**
     * @param array $value
     * @return $this
     */
    public function setMetadata(array $value)
    {
        return $this->setParameter('metadata', $value);
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->getParameter('orderNumber');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setOrderNumber($value)
    {
        return $this->setParameter('orderNumber', $value);
    }

    /**
     * @return string
     */
    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCustomerReference($value)
    {
        return $this->setParameter('customerReference', $value);
    }

    /**
     * @return string
     */
    public function getSequenceType()
    {
        return $this->getParameter('sequenceType');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setSequenceType($value)
    {
        return $this->setParameter('sequenceType', $value);
    }

    /**
     * A list of items in this order
     *
     * @return Item[]|null A bag containing items in this order
     */
    public function getItems()
    {
        return $this->getParameter('items');
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'amount', 'locale', 'card', 'items', 'currency', 'orderNumber', 'returnUrl');

        $data = [];
        $data['amount'] = $this->createAmountObject($this->getAmount());
        $data['orderNumber'] = (string) $this->getOrderNumber();
        $data['redirectUrl'] = $this->getReturnUrl();
        $data['method'] = $this->getPaymentMethod();
        $data['metadata'] = $this->getMetadata();
        $data['payment'] = [];
        $data['lines'] = [];

        if ($this->getTransactionId()) {
            $data['metadata']['transactionId'] = $this->getTransactionId();
        }

        if ($issuer = $this->getIssuer()) {
            $data['issuer'] = $issuer;
        }

        if ($webhookUrl = $this->getNotifyUrl()) {
            $data['webhookUrl'] = $webhookUrl;
        }

        if ($locale = $this->getLocale()) {
            $data['locale'] = $locale;
        }

        if ($items = $this->getItems()) {
            $data['lines'] = $this->getLines($items);
        }

        if ($card = $this->getCard()) {
            $data += $this->getCardData($card);
        }

        if ($customerReference = $this->getCustomerReference()) {
            $data['payment']['customerId'] = $customerReference;
        }

        if ($sequenceType = $this->getSequenceType()) {
            $data['payment']['sequenceType'] = $sequenceType;
        }

        return $data;
    }

    protected function getCardData(CreditCard $card)
    {
        $data = [];

        $data['billingAddress'] = [
            'givenName' => $card->getFirstName(),
            'familyName' => $card->getLastName(),
            'email' => $card->getEmail(),
            'streetAndNumber' => $card->getAddress1(),
            'streetAdditional' => $card->getAddress2(),
            'postalCode' => $card->getPostcode(),
            'city' => $card->getCity(),
            'region' => $card->getState(),
            'country' => $card->getCountry(),
        ];

        if ($card->getShippingAddress1()) {
            $data['shippingAddress'] = [
                'givenName' => $card->getShippingFirstName(),
                'familyName' => $card->getShippingLastName(),
                'email' => $card->getEmail(),
                'streetAndNumber' => $card->getShippingAddress1(),
                'streetAdditional' => $card->getShippingAddress2(),
                'postalCode' => $card->getShippingPostcode(),
                'city' => $card->getShippingCity(),
                'region' => $card->getShippingState(),
                'country' => $card->getShippingCountry(),
            ];
        }

        if ($card->getCompany()) {
            $data['organizationName'] = $card->getCompany();
        }

        return $data;
    }

    protected function getLines(ItemBag $items)
    {
        $lines = [];
        foreach ($items as $item) {
            $vatRate = $item->getVatRate();
            $totalAmount = $item->getQuantity() * $item->getPrice();
            $vatAmount = round($totalAmount * ($vatRate / (100 + $vatRate)), $this->getCurrencyDecimalPlaces());

            $data = [
                'type' => $item->getType(),
                'name' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'unitPrice' => $this->createAmountObject($item->getPrice()),
                'discountAmount' => $this->createAmountObject($item->getDiscountAmount()),
                'totalAmount' => $this->createAmountObject($totalAmount),
                'vatRate' => $vatRate,
                'vatAmount' => $this->createAmountObject($vatAmount),
                'sku' => $item->getSku(),
            ];

            // Strip null values
            $lines[] = array_filter($data);
        }

        return $lines;
    }

    /**
     * @param array $data
     * @return ResponseInterface|PurchaseResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::POST, '/orders', $data);

        return $this->response = new CreateOrderResponse($this, $response);
    }
}
