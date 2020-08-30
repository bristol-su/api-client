<?php


namespace BristolSU\ApiClient\Authentication;

use BristolSU\ApiClient\Contracts\AccessTokenStore;
use BristolSU\ApiToolkit\Contracts\Authenticator;
use BristolSU\ApiToolkit\Contracts\HttpClient;
use DateInterval;
use DateTime;

class BearerTokenAuthenticator implements Authenticator
{

    private $authToken;

    public function __construct(string $authToken)
    {
        $this->authToken = $authToken;
    }

    public function authenticate(HttpClient $client): HttpClient
    {
        $client->global()->addHeader('Authorization',
          sprintf('Bearer %s', $this->authToken)
        );

        return $client;
    }

}
