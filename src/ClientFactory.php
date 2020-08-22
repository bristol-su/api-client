<?php

namespace BristolSU\ApiClient;

use BristolSU\ApiToolkit\Contracts\ApiClientManager;
use BristolSU\ApiToolkit\Contracts\HttpClient;
use BristolSU\ApiToolkit\Contracts\Authenticator;

class ClientFactory
{

    public static function create(array $clients, HttpClient $httpClient, Authenticator $authenticator): Client
    {
        $apiClientManager = static::createApiClientManager();
        foreach($clients as $client) {
            $apiClientManager->register($client);
        }

        $httpClient = $authenticator->authenticate($httpClient);

        $clientResourceFactory = static::createClientResourceFactory($httpClient);
        $clientResourceGroupFactory = static::createClientResourceGroupFactory($clientResourceFactory);

        return new \BristolSU\ApiClient\Client($apiClientManager, $clientResourceGroupFactory);
    }

    protected static function createApiClientManager(): ApiClientManager
    {
        return new \BristolSU\ApiToolkit\ApiClientManager();
    }

    protected static function createClientResourceFactory(HttpClient $httpClient): \BristolSU\ApiToolkit\Contracts\ClientResourceFactory
    {
        return new ClientResourceFactory($httpClient);
    }

    protected static function createClientResourceGroupFactory(\BristolSU\ApiToolkit\Contracts\ClientResourceFactory $clientResourceFactory)
    {
        return new ClientResourceGroupFactory($clientResourceFactory);
    }

}
