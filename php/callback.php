<?php

$orderId = $_GET['orderId'];
$data = file_get_contents('php://input');
if ($data) {
    $params = json_decode($data, true);
    $invoice = $params['invoice'];

    //save number of confirmations to DB: $params['confirmations']

    if ($params['confirmations'] >= $params['maxConfirmations']) {
        $amountPaid = $params['transaction_amount'];
        // check is order not already marked as paid
        // compare $amountPaid with order total
        // compare $invoice with one saved in the database to ensure callback is legitimate
        // do other required checks
        // mark the order as paid
        die('ok'); //stop further callbacks
    } else {
        die("waiting for confirmations");
    }
}