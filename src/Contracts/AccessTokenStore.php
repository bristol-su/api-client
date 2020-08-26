<?php

namespace BristolSU\ApiClient\Contracts;

use DateTime;

interface AccessTokenStore
{

    public function hasAccessToken(): bool;

    public function hasRefreshToken(): bool;

    public function hasExpiresAt(): bool;

    public function hasExpired(): bool;

    public function setAccessToken(string $accessToken);

    public function setRefreshToken(string $refreshToken);

    public function setExpiresAt(DateTime $expiresAt);

    public function getAccessToken(): string;

    public function getRefreshToken(): string;

    public function getExpiresAt(): DateTime;

}
