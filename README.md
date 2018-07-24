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


## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/thephpleague/omnipay-mollie/issues),
or better yet, fork the library and submit a pull request.
