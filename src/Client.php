<?php

namespace BristolSU\ApiClient;

use BristolSU\ApiClient\Contracts\ApiClient;
use BristolSU\ApiToolkit\ApiClientManager;
use BristolSU\ApiToolkit\Contracts\ClientResourceGroupFactory;
use BristolSU\ApiToolkit\Contracts\ClientResourceGroup;

class Client implements ApiClient
{

    /**
     * @var ApiClientManager
     */
    private $apiClientManager;
    /**
     * @var ClientResourceGroupFactory
     */
    private $clientResourceGroupFactory;

    public function __construct(ApiClientManager $apiClientManager, ClientResourceGroupFactory $clientResourceGroupFactory)
    {
        $this->apiClientManager = $apiClientManager;
        $this->clientResourceGroupFactory = $clientResourceGroupFactory;
    }

    /**
     * Call the underlying resource group class
     *
     * @param $name
     * @param $arguments
     *
     * @return ClientResourceGroup
     */
    public function __call($name, $arguments)
    {
        return $this->clientResourceGroupFactory->create(
          $this->apiClientManager->get($name)
        );
    }

}
