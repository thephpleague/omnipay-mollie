# Omnipay: Mollie

**Mollie driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/thephpleague/omnipay-mollie.png?branch=master)](https://travis-ci.org/thephpleague/omnipay-mollie)
[![Latest Stable Version](https://poser.pugx.org/omnipay/mollie/version.png)](https://packagist.org/packages/omnipay/mollie)
[![Total Downloads](https://poser.pugx.org/omnipay/mollie/d/total.png)](https://packagist.org/packages/omnipay/mollie)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements Mollie support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply require `league/omnipay` and `omnipay/mollie` with Composer:

```
composer require league/omnipay omnipay/mollie
```


## Basic Usage

The following gateways are provided by this package:

* Mollie

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

### Basic purchase example

```php
$gateway = \Omnipay\Omnipay::create('Mollie');  
$gateway->setApiKey('test_dHar4XY7LxsDOtmnkVtjNVWXLSlXsM');

$response = $gateway->purchase(
    [
        "amount" => "10.00",
        "currency" => "EUR",
        "description" => "My first Payment",
        "returnUrl" => "https://webshop.example.org/mollie-return.php"
    ]
)->send();

// Process response
if ($response->isSuccessful()) {

    // Payment was successful
    print_r($response);

} elseif ($response->isRedirect()) {

    // Redirect to offsite payment gateway
    $response->redirect();

} else {

    // Payment failed
    echo $response->getMessage();
}
```

### Example using the Order API

1. Create the order and pass the order items in the parameters.

```php
$response = $gateway->createOrder(
    [
           'amount'       => '1027.99',
            'currency'     => 'EUR',
            'orderNumber'  => '1337',
            'lines'        => [
                [
                    'type' => 'physical',
                    'sku' => '5702016116977',
                    'name' => 'LEGO 42083 Bugatti Chiron',
                    'productUrl' => 'https://shop.lego.com/nl-NL/Bugatti-Chiron-42083',
                    'imageUrl' => 'https://sh-s7-live-s.legocdn.com/is/image//LEGO/42083_alt1?$main$',
                    'quantity' => 2,
                    'vatRate' => '21.00',
                    'unitPrice' => '399.00',
                    'totalAmount' => '698.00',
                    'discountAmount' => '100.00',
                    'vatAmount' => '121.14',
                ],
                [
                    'type' => 'physical',
                    'sku' => '5702015594028',
                    'name' => 'LEGO 42056 Porsche 911 GT3 RS',
                    'productUrl' => 'https://shop.lego.com/nl-NL/Porsche-911-GT3-RS-42056',
                    'imageUrl' => 'https://sh-s7-live-s.legocdn.com/is/image/LEGO/42056?$PDPDefault$',
                    'quantity' => 1,
                    'vatRate' => '21.00',
                    'unitPrice' => '329.99',
                    'totalAmount' => '329.99',
                    'vatAmount' => '57.27',
                ]
            ],
            'card' => [
                'company' => 'Mollie B.V.',
                'email' => 'norris@chucknorrisfacts.net',
                'birthday' => '1958-01-31',
                'billingTitle' => 'Dhr',
                'billingFirstName' => 'Piet',
                'billingLastName' => 'Mondriaan',
                'billingAddress1' => 'Keizersgracht 313',
                'billingCity' => 'Amsterdam',
                'billingPostcode' => '1234AB',
                'billingState' => 'Noord-Holland',
                'billingCountry' => 'NL',
                'billingPhone' => '+31208202070',
                'shippingTitle' => 'Mr',
                'shippingFirstName' => 'Chuck',
                'shippingLastName' => 'Norris',
                'shippingAddress1' => 'Prinsengracht 313',
                'shippingAddress2' => '4th floor',
                'shippingCity' => 'Haarlem',
                'shippingPostcode' => '5678AB',
                'shippingState' => 'Noord-Holland',
                'shippingCountry' => 'NL',
            ],
            'metadata' => [
                'order_id' => '1337',
                'description' => 'Lego cars',
            ],
            'locale' => 'nl_NL',
            'returnUrl'    => 'https://example.org/redirect',
            'notifyUrl'    => 'https://example.org/webhook',
            'paymentMethod' => 'klarnapaylater',
            'billingEmail' => 'piet@mondriaan.com',
    ]
)->send();

// Process response
if ($response->isSuccessful()) {

    // Payment was successful
    print_r($response);

} elseif ($response->isRedirect()) {

    // Redirect to offsite payment gateway
    $response->redirect();

} else {

    // Payment failed
    echo $response->getMessage();
}
```

2. On return/notify, complete the order. This will not always be completed, because for Klarna the shipments needs to be created first.


```php
$response = $gateway->completeOrder(
    [
        "transactionReference" => "ord_xxxx",
    ]
)->send();
```

3. When shipping the items, create the shipment for this order. You can leave the `items` emtpy to ship all items.

```php
$response = $gateway->createShipment(
    [
        "transactionReference" => "ord_xxx",
        'items' => [
            [
                'id' => 'odl_xxx',
                'quantity' => 1,
            ]
        ]
    ]
)->send();
```

4. As long as the order is `created`, `authorized` or `shipping`, it may be cancelled (voided) 

```php
$response = $gateway->void(["transactionReference" => "ord_xxx"])->send();
```

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/thephpleague/omnipay-mollie/issues),
or better yet, fork the library and submit a pull request.
