<h3>Yopay API</h3>
This API allows to accept <b>YoCoin</b> payments. More details can be found on our website: https://yopay.tech/

<h3>API Keys</h3>
In order to use the system you need an API key. Getting a key is free and easy, sign up here:
https://yopay.tech

<h3>Multiple Currencies</h3>
Once registered, you can manage the currencies you want to integrate in the Membership area / Currencies. 
Please enable the currencies there before using this API.

<h3>Get Currencies</h3>
<h4>Get a list of enabled currencies with this GET request:</h4>
<table border="0" cellspacing="0" cellpadding="10" >
        <tbody><tr>
            <td>GET</td>
            <td>https://api.yopay.tech/currencies/?token={token}</td>
        </tr>
    </tbody>
</table>
<h4>Parameters:</h4>
<table>
  <tbody>
  <tr>
      <td>token</td>
      <td>API Secret Key</td>
    </tr>
</tbody></table>

<h3>Create payment request</h3>
<h4>Use GET query to create payment request:</h4>
<table border="0" cellspacing="0" cellpadding="10" >
        <tbody><tr>
            <td>GET</td>
            <td>https://api.yopay.tech/{crypto}/payment/{callback_url}/?token={token}</td>
        </tr>
    </tbody>
</table>
<h4>Parameters:</h4>
<table>
  <tbody>
  <tr>
      <td>crypto</td>
      <td>Crypto currency to accept (yoc)</td>
    </tr>
  <tr>
    <td>token</td>
    <td>API Secret Key</td>
  </tr>
</tbody></table>
<h4>Optional parameters:</h4>
<table>
  <tr>
        <td>callback_url</td>
        <td>Your server callback url (urlencoded) to get information about payment</td>
    </tr>
</table>


<h4>Example request URL:</h4>
<a href="https://api.yopay.tech/yoc/payment/http%3A%2F%2Fputsreq.com%2FUv8u7ofxXDWVoaVawDWd/?token=YOURSECRET">
https://api.yopay.tech/yoc/payment/http%3A%2F%2Fputsreq.com%2FUv8u7ofxXDWVoaVawDWd/?token=YOURSECRET</a><br>
<h5>Or without callback_url:</h5>
<a href="https://api.yopay.tech/yoc/payment/?token=YOURSECRET">
https://api.yopay.tech/yoc/payment/?token=YOURSECRET</a><br>
 
<h4>Response:</h4>
<p>The API always responds with a JSON string. [data] collection contains the important values:
[address] is the payment address to show to the customer
[invoice] is our inner payment identifier, keep it in a safe place and never disclose to your clients.</p>

<h4>Response example:</h4>
<p>

```json
{
    "success": true,
    "data": {
        "invoice": "d1ddf6e3767030b08032cf2eae403600",
        "address": "0x2073eb3be1a41908e0353427da7f16412a01ae71"
    }
}
```

<h4>PHP example:</h4> More examples: <a href="nodejs">Node.js</a>

```php
require('yopay.php');

define('API_SECRET', 'your_secret_from_cabinet');
define('API_URL', 'https://api.yopay.tech/');

$yo = new YopayApi(API_SECRET, API_URL);

$orderId = 12345;
$address = $yo->getAddress($orderId);

if ($address['success'] == false) die('Error: ' . $address['error']);
$data = $address['data'];
$invoiceId = $data['invoice']; //save it where you need

die('Please send your coins to address: ' . $data['address']);

```

<h3>Callback</h3>
A callback is sent every time a new block is mined. It stops when transaction get maxConfirmations confirmations. See code sample below.
<h4>Callback request data example:</h4>

```json
{
	"transaction_amount": 2000000000000000000,
	"transaction_hash": "0xfd4609159efea3804335e4a24a6cff5aab31e6f81ae36bb07869cc8b5536827c",
	"block_hash": "0xd5800a894ac462cd3338f24068a5b527996b0427d47b28b04a58fed4b9e15e8f",
	"block_number": 140,
	"address": "0x5FB2C28b53847cA67601Ef4167254FE907abB501",
	"callback": "http://work.loc/t.php",
	"blockchain": "yoc",
	"status": "complete",
	"confirmations": 13,
	"invoice": "5af73d4367cb2423704deba1",
	"maxConfirmations": 4
}
```
<h4>PHP example:</h4> More examples: <a href="nodejs">Node.js</a>

```php

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
```

<h3>Get Invoice Info and Status</h3>

<h4>Use GET query to obtain information about invoice which already stop sent callback requests:</h4>
<table border="0" cellspacing="0" cellpadding="10" >
        <tbody><tr>
            <td>GET</td>
            <td><a href="https://api.yopay.tech/invoice/{invoice}/?token={token}">https://api.yopay.tech/invoice/{invoice}/?token={token}</a></td>
        </tr>
    </tbody>
</table>
<h4>Parameters:</h4>
<table>
  <tbody>
  <tr>
      <td>invoice</td>
      <td>Invoice ID from Create payment request</td>
    </tr>
  <tr>
    <td>token</td>
    <td>API Secret Key</td>
  </tr>
</tbody></table>

<h4>Response:</h4>
The API returns a JSON string containing the information about invoice that same as data sent to callback url.

<h4>Response example:</h4>

```json
{
    "transaction_amount": 2000000000000000000,
    "transaction_hash": "0xfd4609159efea3804335e4a24a6cff5aab31e6f81ae36bb07869cc8b5536827c",
    "block_hash": "0xd5800a894ac462cd3338f24068a5b527996b0427d47b28b04a58fed4b9e15e8f",
    "block_number": 140,
    "address": "0x5FB2C28b53847cA67601Ef4167254FE907abB501",
    "callback": "http://your_domain.com/callback.php?orderId=123",
    "blockchain": "yoc",
    "status": "complete",
    "confirmations": 13,
    "invoice": "5af73d4367cb2423704deba1",
    "maxConfirmations": 4
}
```

### What to use as a payout address?
You will need payout addresses for all crypto currencies you want to accept. Only you will have access to your payout wallets.
You can use any online wallet, service or exchange of your choice.

