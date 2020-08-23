<?php

namespace BristolSU\ApiClient;

use BristolSU\ApiToolkit\Contracts\HttpClient;
use BristolSU\ApiToolkit\Contracts\HttpClient as HttpClientInterface;
use BristolSU\ApiToolkit\Contracts\HttpClientFactory as HttpClientFactoryInterface;
use Psr\Http\Client\ClientInterface;

class GuzzleHttpClientFactory
{

    public static function create(ClientInterface $client): HttpClientInterface
    {
        return new \BristolSU\ApiClient\GuzzleHttpClient($client, new HttpClientConfig());
    }

}
