<?php

namespace BristolSU\ApiClient;

use BristolSU\ApiToolkit\Contracts\ClientResource;
use BristolSU\ApiToolkit\Contracts\HttpClient;

class ClientResourceFactory implements \BristolSU\ApiToolkit\Contracts\ClientResourceFactory
{

    /**
     * @var GuzzleHttpClient
     */
    private $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function create(string $class, array $arguments): ClientResource
    {
        $clientResource = new $class(...$arguments);
        $clientResource->setClient($this->httpClient);
        return $clientResource;
    }
}
