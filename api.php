<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('POST', 'https://api.verified.africa/sfx-verify/v3/id-service/', [
  'body' => '{"searchParameter":"02730846093","verificationType":"NIN-SEARCH"}',
  'headers' => [
    'accept' => 'application/json',
    'apiKey' => 'KC69ZuFxVEsSpld69koD',
    'content-type' => 'application/json',
    'userid' => '1628022119761',
  ],
]);

echo $response->getBody();