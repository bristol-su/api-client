<?php


namespace BristolSU\ApiClient\Authentication;

use BristolSU\ApiClient\Contracts\AccessTokenStore;
use BristolSU\ApiToolkit\Contracts\Authenticator;
use BristolSU\ApiToolkit\Contracts\HttpClient;
use DateInterval;
use DateTime;

class PassportPasswordAuthenticator implements Authenticator
{

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

    /**
     * @var AccessTokenStore
     */
    private $accessTokenStore;

    public function __construct(int $clientId, string $clientSecret, string $email, string $password, AccessTokenStore $accessTokenStore)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->email = $email;
        $this->password = $password;
        $this->accessTokenStore = $accessTokenStore;
    }

    public function authenticate(HttpClient $client): HttpClient
    {
        $this->loadAccessToken($client);

        $client->global()->addHeader('Authorization',
          sprintf('Bearer %s', $this->accessTokenStore->getAccessToken())
        );

        return $client;
    }

    private function loadAccessToken(HttpClient $client)
    {
        if (
          $this->accessTokenStore->hasAccessToken()
          && $this->accessTokenStore->hasExpiresAt()
          && $this->accessTokenStore->hasExpired()
          && $this->accessTokenStore->hasRefreshToken()) {
            $this->refreshAccessToken($client);
        } elseif (!$this->accessTokenStore->hasAccessToken()) {
            $this->createAccessToken($client);
        }
    }

    private function createAccessToken(HttpClient $client)
    {
        $client->config()->addBody([
          'grant_type' => 'password',
          'client_id' => $this->clientId,
          'client_secret' => $this->clientSecret,
          'username' => $this->email,
          'password' => $this->password,
          'scope' => '',
        ]);
        $this->makeRequest($client);
    }

    private function refreshAccessToken(HttpClient $client)
    {
        $client->config()->addBody([
          'grant_type' => 'refresh_token',
          'client_id' => $this->clientId,
          'client_secret' => $this->clientSecret,
          'refresh_token' => $this->accessTokenStore->getRefreshToken(),
          'scope' => '',
        ]);
        $this->makeRequest($client);
    }

    private function makeRequest(HttpClient $client)
    {
        $response = $client->post('oauth/token');

        $access = json_decode((string)$response->getBody(), true);

        $this->accessTokenStore->setAccessToken($access['access_token']);
        $this->accessTokenStore->setRefreshToken($access['refresh_token']);
        $this->accessTokenStore->setExpiresAt(
          (new DateTime())
            ->add(
              new DateInterval(sprintf('PT%sS', $access['expires_in']))
            )
        );
    }

}
