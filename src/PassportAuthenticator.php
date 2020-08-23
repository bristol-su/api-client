<?php


namespace BristolSU\ApiClient;

use BristolSU\ApiToolkit\Contracts\Authenticator;
use BristolSU\ApiToolkit\Contracts\HttpClient;
use GuzzleHttp\Exception\ServerException;

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
        $client->config()->addBody([
          'grant_type' => 'password',
          'client_id' => $this->clientId,
          'client_secret' => $this->clientSecret,
          'username' => $this->email,
          'password' => $this->password,
          'scope' => '',
        ]);
        try {
            var_dump($client->post('oauth/token')->getBody()->getContents());
        }
        catch (ServerException $e) {
            var_dump($e->getResponse()->getBody()->getContents());
        }
        die();
        return $client;
    }


}
