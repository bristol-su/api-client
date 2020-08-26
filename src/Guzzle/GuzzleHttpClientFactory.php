<?php

namespace BristolSU\ApiClient\Guzzle;

use BristolSU\ApiToolkit\Contracts\HttpClient;
use BristolSU\ApiToolkit\Contracts\HttpClient as HttpClientInterface;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;

class GuzzleHttpClientFactory
{

    public static function create(string $baseUrl): HttpClientInterface
    {
        $client = new Client(['base_uri' => $baseUrl]);
        return new \BristolSU\ApiClient\Guzzle\GuzzleHttpClient($client);
    }

}
