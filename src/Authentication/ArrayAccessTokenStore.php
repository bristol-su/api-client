<?php


namespace BristolSU\ApiClient\Authentication;


use BristolSU\ApiClient\Contracts\AccessTokenStore;
use DateTime;

class ArrayAccessTokenStore implements AccessTokenStore
{

    private $accessToken = null;

    private $refreshToken = null;

    private $expiresAt = null;

    public function hasAccessToken(): bool
    {
        return $this->accessToken !== null;
    }

    public function hasRefreshToken(): bool
    {
        return $this->refreshToken !== null;
    }

    public function hasExpiresAt(): bool
    {
        return $this->expiresAt !== null;
    }

    public function hasExpired(): bool
    {
        return new DateTime() > $this->getExpiresAt();
    }

    public function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function setRefreshToken(string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    public function setExpiresAt(DateTime $expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getExpiresAt(): DateTime
    {
        return $this->expiresAt;
    }
}
