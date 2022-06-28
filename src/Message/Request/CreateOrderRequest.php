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
     * Lines can contain a negative amount (discounts etc.)
     *
     * @var bool
     */
    protected $negativeAmountAllowed = true;

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
    public function getBillingEmail()
    {
        return $this->getParameter('billingEmail');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setBillingEmail($value)
    {
        return $this->setParameter('billingEmail', $value);
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
     * @return string
     */
    public function getCardToken()
    {
        return $this->getParameter('cardToken');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCardToken($value)
    {
        return $this->setParameter('cardToken', $value);
    }

    /**
     * Alias for lines
     *
     * @param $items
     * @return $this
     */
    public function setLines($items)
    {
        return $this->setItems($items);
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
        $data['amount'] = [
            'value' => $this->getAmount(),
            'currency' => $this->getCurrency()
        ];

        if ($card = $this->getCard()) {
            $data += $this->getCardData($card);
        }

        $data['metadata'] = $this->getMetadata();
        if ($this->getTransactionId()) {
            $data['metadata']['transactionId'] = $this->getTransactionId();
        }

        if ($card && $birthday = $card->getBirthday()) {
            $data['consumerDateOfBirth'] = $birthday;
        }

        $data['locale'] = $this->getLocale();
        $data['orderNumber'] = (string) $this->getOrderNumber();
        $data['redirectUrl'] = $this->getReturnUrl();
        $data['webhookUrl'] = $this->getNotifyUrl();
        $data['method'] = $this->getPaymentMethod();

        $data['lines'] = [];
        if ($items = $this->getItems()) {
            $data['lines'] = $this->getLines($items);
        }

        $data['payment'] = [];
        if ($issuer = $this->getIssuer()) {
            $data['payment']['issuer'] = $issuer;
        }

        if ($customerReference = $this->getCustomerReference()) {
            $data['payment']['customerId'] = $customerReference;
        }

        if ($sequenceType = $this->getSequenceType()) {
            $data['payment']['sequenceType'] = $sequenceType;
        }

        if ($cardToken = $this->getCardToken()) {
            $data['payment']['cardToken'] = $cardToken;
        }

        return array_filter($data);
    }

    protected function getCardData(CreditCard $card)
    {
        $data = [];

        $data['billingAddress'] = array_filter([
            'organizationName' => $card->getCompany(),
            'streetAndNumber' => $card->getAddress1(),
            'streetAdditional' => $card->getAddress2(),
            'city' => $card->getCity(),
            'region' => $card->getState(),
            'postalCode' => $card->getPostcode(),
            'country' => $card->getCountry(),
            'title' => $card->getTitle(),
            'givenName' => $card->getFirstName(),
            'familyName' => $card->getLastName(),
            'email' => $this->getBillingEmail() ?: $card->getEmail(),
            'phone' => $card->getPhone(),
        ]);

        if ($card->getShippingAddress1()) {
            $data['shippingAddress'] = array_filter([
                'organizationName' => $card->getCompany(),
                'streetAndNumber' => $card->getShippingAddress1(),
                'streetAdditional' => $card->getShippingAddress2(),
                'city' => $card->getShippingCity(),
                'region' => $card->getShippingState(),
                'postalCode' => $card->getShippingPostcode(),
                'country' => $card->getShippingCountry(),
                'title' => $card->getShippingTitle(),
                'givenName' => $card->getShippingFirstName(),
                'familyName' => $card->getShippingLastName(),
                'email' => $card->getEmail(),
                'phone' => $card->getShippingPhone(),
            ]);
        }

        return $data;
    }

    protected function getLines(ItemBag $items)
    {
        $lines = [];
        foreach ($items as $item) {
            $vatRate = $item->getVatRate();
            $totalAmount = $item->getTotalAmount();
            $vatAmount = $item->getVatAmount();

            if (null === $totalAmount) {
                $totalAmount = $item->getQuantity() * $item->getPrice();
            }

            if (null === $vatAmount) {
                $vatAmount =  round($totalAmount * ($vatRate / (100 + $vatRate)), $this->getCurrencyDecimalPlaces());
            }

            $data = [
                'type' => $item->getType(),
                'sku' => $item->getSku(),
                'name' => $item->getName(),
                'productUrl' => $item->getProductUrl(),
                'imageUrl' => $item->getImageUrl(),
                'quantity' => (int) $item->getQuantity(),
                'vatRate' => $vatRate,
                'unitPrice' => $this->createAmountObject($item->getUnitPrice()),
                'totalAmount' => $this->createAmountObject($totalAmount),
                'discountAmount' => $this->createAmountObject($item->getDiscountAmount()),
                'vatAmount' => $this->createAmountObject($vatAmount),
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
