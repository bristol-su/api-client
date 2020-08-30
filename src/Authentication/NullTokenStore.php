<?php


namespace BristolSU\ApiClient\Authentication;


use BristolSU\ApiClient\Contracts\AccessTokenStore;
use DateTime;

class NullTokenStore implements AccessTokenStore
{

    public function hasAccessToken(): bool
    {
        return false;
    }

    public function hasRefreshToken(): bool
    {
        return false;
    }

    public function hasExpiresAt(): bool
    {
        return false;
    }

    public function hasExpired(): bool
    {
        return true;
    }

    public function setAccessToken(string $accessToken)
    {
    }

    public function setRefreshToken(string $refreshToken)
    {
    }

    public function setExpiresAt(DateTime $expiresAt)
    {
    }

    public function getAccessToken(): string
    {
        return '';
    }

    public function getRefreshToken(): string
    {
        return '';
    }

    public function getExpiresAt(): DateTime
    {
        return new DateTime();
    }
}
