    # Yopay API
This API allows to accept <b>YoCoin</b> payments. More details can be found on our website: https://yopay.tech/

#### API Keys
In order to use the system you need an API key. Getting a key is free and easy, sign up here:
https://yopay.tech

#### Multiple Currencies
Once registered, you can manage the currencies you want to integrate in the Membership area / Currencies. 
Please enable the currencies there before using this API.

## API Resources


### GET /currencies/?token={token}
Get a list of enabled currencies.

**Example:** https://api.yopay.tech/currencies/?token=WKt2skCsqns6666666666CJv0m4DUkX

**Headers:** Content-Type: application/json

**Parameters:**
 - token - API Secret Key

**Response body:**
```json
{
   "success": true,
   "data": [
       "yoc",
       "eth"
   ]
}
```


### GET /{crypto}/payment/?token={token}
### GET /{crypto}/payment/{callback_url}/?token={token}

Create payment request

**Examples:**
 - Without callback: 
  https://api.yopay.tech/yoc/payment/?token=WKt2skCsqns6666666666CJv0m4DUkX
 - With callback: https://api.yopay.tech/yoc/payment/http%3A%2F%2Fexample.com%2Fcb.php/?token=WKt2skCsqns6666666666CJv0m4DUkX

**Headers:** Content-Type: application/json

**Parameters:**
 - token - API Secret Key
 - crypto - Crypto currency to accept

**Optional parameters:**
 - callback_url - Your server callback url (urlencoded) to get information about payment

**Response body:**
 
*The API always responds with a JSON string. [data] collection contains the important values:*
 - *[address] is the payment address to show to the customer*
 - *[invoice] is our inner payment identifier, keep it in a safe place and never disclose to your clients.*

```json
{
    "success": true,
    "data": {
        "invoice": "d1ddf6e3767030b06666666eae403600",
        "address": "0xf75574f061cd66666666666666666666666666f2"
    }
}
```

**PHP example:**

```php
require('yopay.php');

define('API_SECRET', 'your_secret_from_cabinet');
define('API_URL', 'https://api.yopay.tech/');

$yo = new YopayApi(API_SECRET, API_URL);

$orderId = 123;
$callback = 'http://example.com/yopay/callback.php?orderId=' . $orderId;

$address = $yo->getAddress($callback);

if ($address['success'] == false) die('Error: ' . $address['error']);
$data = $address['data'];
$invoiceId = $data['invoice']; //save it where you need

die('Please send your coins to address: ' . $data['address']);
```

**Callback example:**

*A callback is sent every time a new block is mined. It stops when transaction get maxConfirmations confirmations. See code sample below.*

```json
{
	"transaction_amount": 2000000000000000000,
	"transaction_hash": "0xfd4609159efea3804335e4a2666666666666666666666bb07869cc8b5536827c",
	"block_hash": "0xd5800a894ac462cd3338f24068a6666666666666666666664a58fed4b9e15e8f",
	"block_number": 140,
	"address": "0xf75574f061cd66666666666666666666666666f2",
	"callback": "http://example.com/cb.php",
	"blockchain": "yoc",
	"status": "complete",
	"confirmations": 13,
	"invoice": "d1ddf6e3767030b06666666eae403600",
	"maxConfirmations": 4
}
```

**PHP callback cb.php example:**

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


### GET /invoice/{invoice}/?token={token}

Get Invoice Info and Status. Obtain information about invoice which already stop sent callback requests

**Example:** https://api.yopay.tech/invoice/d1ddf6e3767030b06666666eae403600/?token=WKt2skCsqns6666666666CJv0m4DUkX 

**Headers:** Content-Type: application/json

**Parameters:**
 - token - API Secret Key
 - invoice - Invoice ID from Create payment request
 
**Response body:**

*The API returns a JSON string containing the information about invoice that same as data sent to callback url.*

```json
{
    "transaction_amount": 2000000000000000000,
    "transaction_hash": "0xfd4609159efea3804335e4a2666666666666666666666bb07869cc8b5536827c",
    "block_hash": "0xd5800a894ac462cd3338f24068a6666666666666666666664a58fed4b9e15e8f",
    "block_number": 140,
    "address": "0xf75574f061cd66666666666666666666666666f2",
    "callback": "http://example.com/cb.php",
    "blockchain": "yoc",
    "status": "complete",
    "confirmations": 13,
    "invoice": "d1ddf6e3767030b06666666eae403600",
    "maxConfirmations": 4
}
```


### GET /rates/{fiat}/?token={token} 

Get Currencies exchange rates. Obtain information about currencies exchange rates against fiat from markets.

**Example:** https://api.yopay.tech/rates/usd/?token=WKt2skCsqns6666666666CJv0m4DUkX

**Headers:** Content-Type: application/json

**Parameters:**
 - token - API Secret Key
 - fiat - Comma separated fiat currencies in which you obtain rates

**Response body:**

*The API returns a JSON string containing the information about selected currencies exchange rates.*

```json
{
    "success": true,
    "data": {
        "USD": {
            "YOC": {
                "coinmarketcap": 0.0203020243,
                "mid": 0.0203020243
            }
        },
        "EUR": {
            "YOC": {
                "coinmarketcap": 1.4806298998,
                "mid": 1.4806298998
            }
        }
    }
}
```

#### What to use as a payout address?

You will need payout addresses for all crypto currencies you want to accept. Only you will have access to your payout wallets.
You can use any online wallet, service or exchange of your choice.


### GET /{crypto}/wallet/?token={token}

Retrieve wallet balance

**Example:** https://api.yopay.tech/yoc/wallet/?token=WKt2skCsqns6666666666CJv0m4DUkX

**Headers:** Content-Type: application/json

**Parameters:**
 - token - API Secret Key
 - crypto - Crypto currency to accept
 
**Response body:**
```json
{
    "success": true,
    "data": {
        "balance": "16220000000000000",
        "wallet": {
            "user": "5afe757f6666666666664d00",
            "cold_wallet": "0x2b30D2903C1366666666666666666666113E27E4",
            "api_access": true,
            "max_amount": null,
            "max_confirmations": 3,
            "min_amount": null,
            "is_active": true,
            "blockchain": "yoc"
        }
    }
}
```


### POST /yoc/withdraw/?token={token}
### POST /yoc/withdraw/{callback_url}/?token={token}

Request withdrawal to cold wallet (You configure it in your cabinet)

**Examples:** 
 - Without callback: https://api.yopay.tech/yoc/withdraw/?token=WKt2skCsqns6666666666CJv0m4DUkX
 - With callback: https://api.yopay.tech/yoc/withdraw/http%3A%2F%2Fexample.com%2Fcb.php/?token=WKt2skCsqns6666666666CJv0m4DUkX

**Headers:** Content-Type: application/json

**Parameters:**
 - token - API Secret Key
- callback_url - Your server callback url (urlencoded) to get information about withdrawal
 

**Request body:**
```json
{
 "address": "0xf75574f061cd66666666666666666666666666f2",
 "amount": "0.02"
}
```

**Response body:**
```json
{
    "success": true,
    "data": "Transaction sent",
    "id": "5b5666666666666666662ed21"
}
```

**Callback example:**

```json
{
  "withdrawal": {
    "txid": "0x8e81cc436666666666666666643801d8f98d4d093c1e5381c031eb55048f92ed",
    "address": "0xf75574f061cd66666666666666666666666666f2",
    "blockchain": "yoc",
    "amount": "0.33",
    "callback": "http:/example.com/cb.php",
    "status": "complete",
    "created": "2018-07-23T12:35:35.394Z",
    "id": "5b55cbb7566666666689fcdb"
  }
}
```


### GET /withdraw/{withdraw}/?token={token}

Retrieve withdrawal details

**Example:** https://api.yopay.tech/withdraw/5b13c41166666fff666666e7/?token=WKt2skCsqns6666666666CJv0m4DUkX

**Headers:** Content-Type: application/json

**Response body:**

```json
{
    "success": true,
    "data": {
        "txid": "0x8e81cc436666666666666666643801d8f98d4d093c1e5381c031eb55048f92ed",
        "address": "0xf75574f061cd66666666666666666666666666f2",
        "blockchain": "yoc",
        "amount": "0.33",
        "callback": "http:/example.com/cb.php",
        "status": "complete",
        "created": "2018-07-23T12:35:35.394Z",
        "id": "5b55cbb7566666666689fcdb"
      }
    }
```


Before usage:
Open settings and populate password and upload you json file
toggle api access on
