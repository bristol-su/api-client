<?php


namespace BristolSU\ApiClient\Authentication;

use BristolSU\ApiClient\Contracts\AccessTokenStore;
use BristolSU\ApiToolkit\Contracts\Authenticator;
use BristolSU\ApiToolkit\Contracts\HttpClient;
use DateInterval;
use DateTime;

class PassportAuthTokenAuthenticator implements Authenticator
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
    private $redirectUri;

    /**
     * @var AccessTokenStore
     */
    private $accessTokenStore;
    /**
     * @var string
     */
    private $state;
    /**
     * @var string
     */
    private $code;

    public function __construct(int $clientId, string $clientSecret, string $redirectUri, string $state, string $code, AccessTokenStore $accessTokenStore)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accessTokenStore = $accessTokenStore;
        $this->redirectUri = $redirectUri;
        $this->state = $state;
        $this->code = $code;
    }

    public static function constructRedirectUrl(int $clientId, string $redirectUri, string $state, string $baseUrl)
    {
        $query = [
          'client_id' => $clientId,
          'redirect_uri' => $redirectUri,
          'response_type' => 'code',
          'scope' => '',
          'state' => $state,
        ];
        return $baseUrl . '/oauth/authorize?' . http_build_query($query);
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
          'grant_type' => 'authorization_code',
          'client_id' => $this->clientId,
          'client_secret' => $this->clientSecret,
          'redirect_uri' => $this->redirectUri,
          'code' => $this->code
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
