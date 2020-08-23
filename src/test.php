<?php

require(__DIR__ . '/../vendor/autoload.php');


$authenticator = new \BristolSU\ApiClient\PassportAuthenticator(
//  1,
  // Laptop
  4,
//  'l2zQ6RUlZJzttUQCpoMxQQhoXL2lf4CPFe5aQA6O',
  // Laptop
  'DDdF7LcVgydXdjVpE07MbPZD4o2awutA62S4alZ5',
  'tobytwigger@hotmail.co.uk',
  'secret'
);
$httpClient = \BristolSU\ApiClient\GuzzleHttpClientFactory::create(new \GuzzleHttp\Client(['base_uri' => 'https://portal.local']));
$authenticator->authenticate($httpClient);
//
//$client = \BristolSU\ApiClient\ClientFactory::create(
//  [
//    BristolSU\ApiClientImplementation\Control\Client::class
//  ],
//  $httpClient,
//  $authenticator);
//
//var_dump($client);
//die();
//
//$result = $client->control()->group();
//
//var_dump($result);
