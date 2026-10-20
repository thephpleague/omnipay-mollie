<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\CreditCard;
use Omnipay\Common\ItemBag;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Mollie\Gateway;
use Omnipay\Mollie\Item;

/**
 * This class holds all the common things for all of Mollie requests.
 *
 * @see https://docs.mollie.com/index
 */
abstract class AbstractMollieRequest extends AbstractRequest
{
    const POST = 'POST';
    const GET = 'GET';
    const DELETE = 'DELETE';

    /**
     * @var string
     */
    protected $apiVersion = "v2";

    /**
     * @var string
     */
    protected $baseUrl = 'https://api.mollie.com/';

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * @return array|null
     */
    public function getVersionStrings()
    {
        return $this->getParameter('versionStrings');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setVersionStrings(array $values)
    {
        return $this->setParameter('versionStrings', $values);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    /**
     * Set the items in this order
     *
     * @param Item[] $items An array of items in this order
     * @return $this
     */
    public function setItems($items)
    {
        $orderItems = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $orderItems[] = new Item($item);
            } elseif ($item instanceof Item) {
                $orderItems[] = $item;
            } else {
                throw new \InvalidArgumentException('Item should be an instance of ' . Item::class);
            }
        }

        return parent::setItems($orderItems);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    protected function sendRequest($method, $endpoint, ?array $data = null)
    {
        $versions = [
            'Omnipay-Mollie/' . Gateway::GATEWAY_VERSION,
            'PHP/' . phpversion(),
        ];

        if ($customVersions = $this->getParameter('versionStrings')) {
            $versions = array_merge($versions, $customVersions);
        }

        $headers = [
            'Accept' => "application/json",
            'Content-Type' => "application/json",
            'Authorization' => 'Bearer ' . $this->getApiKey(),
            'User-Agent' => implode(' ', $versions),
        ];

        if (function_exists("php_uname")) {
            $headers['X-Mollie-Client-Info'] = php_uname();
        }

        $response = $this->httpClient->request(
            $method,
            $this->baseUrl . $this->apiVersion . $endpoint,
            $headers,
            ($data === null || $data === []) ? null : json_encode($data)
        );

        return json_decode($response->getBody(), true);
    }


    /**
     * Returns the billing and shipping address from the card for the Orders and Payments API
     *
     * @param CreditCard $card
     * @return array
     */
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
            'email' => $this->getParameter('billingEmail') ?: $card->getEmail(),
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

    /**
     * Returns the order lines for the Orders and Payments API
     *
     * @param ItemBag $items
     * @param string $nameKey Key of the item name, "name" for the Orders API and "description" for the Payments API
     * @return array
     */
    protected function getLines(ItemBag $items, $nameKey = 'name')
    {
        // Lines can contain negative amounts (discounts etc.), the amount of the request itself can't
        $negativeAmountAllowed = $this->negativeAmountAllowed;
        $this->negativeAmountAllowed = true;

        try {
            return $this->createLines($items, $nameKey);
        } finally {
            $this->negativeAmountAllowed = $negativeAmountAllowed;
        }
    }

    /**
     * @param ItemBag $items
     * @param string $nameKey
     * @return array
     */
    private function createLines(ItemBag $items, $nameKey)
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
                $nameKey => $item->getName(),
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

    protected function createAmountObject($amount)
    {
        return isset($amount) ? [
            'currency' => $this->getCurrency(),
            'value' => $this->formatCurrency($amount),
        ] : null;
    }
}
