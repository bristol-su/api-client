<?php


namespace BristolSU\ApiClient;

use BristolSU\ApiToolkit\Contracts\Authenticator;
use BristolSU\ApiToolkit\Contracts\HttpClient;

class PassportAuthenticator implements Authenticator
{

    // TODO Cache access token for future use

    /**
     * @var int
     */
    private $clientId;
    /**
     * @var string
     */
    private $clientSecret;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $password;

    public function __construct(int $clientId, string $clientSecret, string $email, string $password)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->email = $email;
        $this->password = $password;
    }

    public function authenticate(HttpClient $client): HttpClient
    {
        $client->addBody([
          'grant_type' => 'password',
          'client_id' => $this->clientId,
          'client_secret' => $this->clientSecret,
          'username' => $this->email,
          'password' => $this->password,
          'scope' => '',
        ]);
        var_dump($client->request('s', 's'));
        die();
        return $client;
    }


}
