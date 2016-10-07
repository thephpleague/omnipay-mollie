<?php
include('vendor/autoload.php');
use Omnipay\Omnipay;

$gateway = Omnipay::create('Mollie');
$gateway->setApiKey('test_B3MKhcyPzM3FnaCtuE3GFfuMUPDGHV');

$response = $gateway->fetchCustomer([
    'apiKey' => 'test_B3MKhcyPzM3FnaCtuE3GFfuMUPDGHV',
    'customerReference' => 'cst_9ST3MW2fv6',
])->send();

echo $response->getCustomerReference();

//$response = $gateway->purchase([
//	'apiKey' => 'test_B3MKhcyPzM3FnaCtuE3GFfuMUPDGHV',
//	'amount' => 10.00,
//	'description' => 'omnipayment',
//	'returnUrl' => 'http://vagrant.nl',
//])->send();
//var_dump($response);
//die();
//
//$response = $gateway->refund([
//	'apiKey' => 'test_B3MKhcyPzM3FnaCtuE3GFfuMUPDGHV',
//	'transactionReference' => 'tr_8Sktv8Dnsn',
//	"description" => "omnifund",
//	"amount" => 0.10,
//])->send();
////var_dump($efundResponse);
//die();

//$formData = array('number' => '4242424242424242', 'expiryMonth' => '6', 'expiryYear' => '2016', 'cvv' => '123');
//$response = $gateway->purchase(array('amount' => '10.00', 'currency' => 'USD', 'card' => $formData))->send();

if ($response->isSuccessful()) {
	// payment was successful: update database
	print_r($response->getData());
//	print_r($response);
} elseif ($response->isRedirect()) {
	// redirect to offsite payment gateway
	$response->redirect();
} else {
	// payment failed: display message to customer
	echo $response->getMessage();
}