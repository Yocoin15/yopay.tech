<?php


class YopayApi
{
    private $apiSecret = 'your_secret_from_cabinet';

    private $apiUrl = 'https://api.yopay.tech/';

    public function __construct($apiSecret, $apiUrl)
    {
        $this->apiSecret = $apiSecret;
        $this->apiUrl = $apiUrl;
    }

    /*
     * Create new invoice and generate new payment address for it
     *
     * @param string $callback callback url
     * @param string $token is currency in which need to create invoice
     *
     * @return array $result
     */
    public function getAddress($callback, $token = 'YOC')
    {
        $callbackUrl = urlencode($callback);

        $token = strtolower($token);

        $url = $this->apiUrl . $token . '/payment/' . $callbackUrl . '/?token=' . $this->apiSecret;

        return $this->makeGetRequest($url);
    }

    /*
     * Get available currencies (tokens) for current apiSecret key;
     *
     * @return array $result of tokens
     */
    public function getCurrencies()
    {
        $url = $this->apiUrl . 'currencies/?token=' . $this->apiSecret;

        return $this->makeGetRequest($url);
    }

    /*
     * Get currencies exchange rates;
     *
     * @param string|array $fiat is fiat currency for get rates
     *
     * @return array $result of rates
     */
    public function getRates($fiat)
    {
        $url = $this->apiUrl . 'rates/' . $fiat . '/?token=' . $this->apiSecret;

        return $this->makeGetRequest($url);
    }

    /*
     * Get Info and status for exists invoice by it id
     *
     * @param string $invoiceId Invoice id got from getAddress request
     *
     * @return array $result
     */
    public function getInvoiceInfo($invoiceId)
    {
        $url = $this->apiUrl . 'invoice/' . $invoiceId . '/?token=' . $this->apiSecret;

        return $this->makeGetRequest($url);
    }

    private function makeGetRequest($url)
    {
        if ($response = @file_get_contents($url)) {
            $response = json_decode($response, true);

            return $response;
        }

        return [
            'success' => false,
            'error' => 'Invalid response'
        ];
    }
}