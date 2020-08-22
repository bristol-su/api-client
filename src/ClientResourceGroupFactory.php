<?php

namespace BristolSU\ApiClient;

use BristolSU\ApiToolkit\Contracts\ClientResourceGroup;
use BristolSU\ApiToolkit\Contracts\ClientResourceFactory;

class ClientResourceGroupFactory implements \BristolSU\ApiToolkit\Contracts\ClientResourceGroupFactory
{

    /**
     * @var ClientResourceFactory
     */
    private $clientResourceFactory;

    public function __construct(ClientResourceFactory $clientResourceFactory)
    {
        $this->clientResourceFactory = $clientResourceFactory;
    }

    public function create(string $class): ClientResourceGroup
    {
        // TODO Check correct class instance
        return new $class($this->clientResourceFactory);
    }

}
