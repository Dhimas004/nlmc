<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('POST', 'https://api.sandbox.midtrans.com/v1/payment-links', [
    'body' => '{"transaction_details":{"order_id":"concert-ticket-09","gross_amount":100000},"usage_limit":2}',
    'headers' => [
        'accept' => 'application/json',
        'authorization' => 'Basic U0ItTWlkLXNlcnZlci0zbW84cmhnUFd1QWM0OE9rd2ZCajF1Ymw6QERoaWFtczEyMw==',
        'content-type' => 'application/json',
    ],
]);

echo $response->getBody();
