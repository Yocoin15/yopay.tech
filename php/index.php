<?php
// Example for YopayApi class usage

require('yopay.php');

define('API_SECRET', 'your_secret_from_cabinet');
define('API_URL', 'https://api.yopay.tech/');

$yo = new YopayApi(API_SECRET, API_URL);

$curencies = $yo->getCurrencies();

if ($curencies['success'] == false) die('Error: ' . $curencies['error']);

$curencies = $curencies['data'];

echo 'Your available currencies is: <pre>';
print_r($curencies);

$rates = $yo->getRates(['usd', 'eur']);

echo 'Exchange rates is: <pre>';
print_r($rates);

if (!in_array('yoc', $curencies)) die('I can`t take payment in YOC');

$address = $yo->getAddress(123);

if ($address['success'] == false) die('Error: ' . $address['error']);
$data = $address['data'];
$invoiceId = $data['invoice']; //save it where you need

die('Please send your coins to address: ' . $data['address']);
