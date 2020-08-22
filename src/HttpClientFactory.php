<?php

namespace BristolSU\ApiClient;

use BristolSU\ApiToolkit\Contracts\HttpClient;
use BristolSU\ApiToolkit\Contracts\HttpClient as HttpClientInterface;
use BristolSU\ApiToolkit\Contracts\HttpClientFactory as HttpClientFactoryInterface;
use Psr\Http\Client\ClientInterface;

class HttpClientFactory implements HttpClientFactoryInterface
{

    public static function create(ClientInterface $client, string $baseUrl): HttpClientInterface
    {
        $httpClient = new \BristolSU\ApiClient\GuzzleHttpClient($client);
        $httpClient->base()->setBaseUrl($baseUrl);
        return $httpClient;
    }

}
