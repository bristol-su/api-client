<?php

require(__DIR__ . '/../vendor/autoload.php');


$authenticator = new \BristolSU\ApiClient\PassportAuthenticator(
  1,
  'l2zQ6RUlZJzttUQCpoMxQQhoXL2lf4CPFe5aQA6O',
  'tobytwigger@hotmail.co.uk',
  'secret'
);
$httpClient = \BristolSU\ApiClient\HttpClientFactory::create(new \GuzzleHttp\Client(), 'https://portal.local');

$client = \BristolSU\ApiClient\ClientFactory::create(
  [
    BristolSU\ApiClientImplementation\Control\Client::class
  ],
  $httpClient,
  $authenticator);


$result = $client->control()->group();

var_dump($result);
