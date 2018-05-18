const app = new (require('express').Router)();

app.post('/yopay/callback/:order', (req, res) => {
    if (req.body && req.params.order) {
        const orderId = req.params.order;
        const data = req.body;
        const invoice = data.invoice;

        //save data.confirmations - number of confirmations to DB

        if (data.confirmations >= data.maxConfirmations) {
            const amountPaid = data.transaction_amount;
            // check is order not already marked as paid
            // compare $amountPaid with order total
            // compare $invoice with one saved in the database to ensure callback is legitimate
            // do other required checks
            // mark the order as paid
            res.send('ok');
        } else {
            res.send('waiting for confirmations');
        }
    } else {
        res.send('error');
    }

});

module.exports = app;