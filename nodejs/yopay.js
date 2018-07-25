const https = require('https');
const API_SECRET = 'your_secret_from_cabinet';
const API_URL = 'https://api.yopay.tech/';

function makeGetRequest(url, callback) {
    https.get(url, function (res) {
        let rawData = '';

        res.on('data', function (chunk) {
            rawData += chunk;
        });

        res.on('end', function () {
            const response = JSON.parse(rawData);
            if (response.success) {
                callback(response.data);
            } else console.error('Error: ' + response.error);
        });
    }).on('error', function (e) {
        console.error(e);
        callback(null);
    });
}

module.exports = {
    getAddress(orderId, token, callback) {
        const callbackUrl = encodeURIComponent('http://YOUR_DOMAIN.com/yopay/callback/' + orderId);

        const url = API_URL + token.toLowerCase() + '/payment/' + callbackUrl + '/?token=' + API_SECRET;

        makeGetRequest(url, callback);
    },

    getInvoiceStatus(invoiceId, callback) {
        const url = API_URL + 'invoice/' + invoiceId + '/?token=' + API_SECRET;

        makeGetRequest(url, callback);
    },

    getCurrencies(callback) {
        const url = API_URL + 'currencies/?token=' + API_SECRET;
        makeGetRequest(url, callback);
    },

    getRates(callback, fiat) {
        const url = API_URL + 'rates/' + fiat + '/?token=' + API_SECRET;
        makeGetRequest(url, callback);
    }

};
