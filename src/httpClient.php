<?php


// Create a cache store
$cache = new \Symfony\Component\Cache\Psr16Cache(
  new \Symfony\Component\Cache\Adapter\FilesystemAdapter()
);

// Set up the authentication. 4 and DDdF7LcVgydXdjVpE07MbPZD4o2awutA62S4alZ5 on laptop (locally)
$authenticator = new \BristolSU\ApiClient\Authentication\PassportPasswordAuthenticator(
  1,
  'l2zQ6RUlZJzttUQCpoMxQQhoXL2lf4CPFe5aQA6O',
  'tobytwigger@hotmail.co.uk',
  'secret',
  new \BristolSU\ApiClient\Authentication\CacheAccessTokenStore($cache)
);

// Create the HttpClient and set defaults
$httpClient = new BristolSU\ApiClient\Cache\CacheHttpClient(
  \BristolSU\ApiClient\Guzzle\GuzzleHttpClientFactory::create('https://portal.local'),
  $cache
);
$httpClient->config()->addHeader('Content-Type', 'application/json');
$httpClient->config()->addHeader('Accept', 'application/json');
$httpClient->config()->verifySSL(false);

// Create the Client itself
$client = \BristolSU\ApiClient\ClientFactory::create(
  [
    BristolSU\ApiClientImplementation\Control\Client::class
  ],
  $httpClient,
  $authenticator
);
