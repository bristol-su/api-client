<?php

namespace BristolSU\ApiClient\Authentication;

use BristolSU\ApiClient\Contracts\AccessTokenStore;
use DateTime;
use Psr\SimpleCache\CacheInterface;

class CacheAccessTokenStore implements AccessTokenStore
{

    const CACHE_KEY = 'BristolSU.ApiClient.Authentication.CacheAccessTokenStore';

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function hasAccessToken(): bool
    {
        return $this->has('access_token');
    }

    public function hasRefreshToken(): bool
    {
        return $this->has('refresh_token');
    }

    public function hasExpiresAt(): bool
    {
        return $this->has('expires_at');
    }

    public function hasExpired(): bool
    {
        return new DateTime() > $this->getExpiresAt();
    }

    public function setAccessToken(string $accessToken)
    {
        $this->set('access_token', $accessToken);
    }

    public function setRefreshToken(string $refreshToken)
    {
        $this->set('refresh_token', $refreshToken);
    }

    public function setExpiresAt(DateTime $expiresAt)
    {
        $this->set('expires_at', $expiresAt->format(DateTime::ATOM));
    }

    public function getAccessToken(): string
    {
        return $this->get('access_token');
    }

    public function getRefreshToken(): string
    {
        return $this->get('refresh_token');
    }

    public function getExpiresAt(): DateTime
    {
        return DateTime::createFromFormat(
          DateTime::ATOM,
          $this->get('expires_at')
        );
    }

    private function get(string $key) {
        return $this->cache->get(
          sprintf('%s.%s', static::CACHE_KEY, $key)
        );
    }

    private function set(string $key, $value)
    {
        return $this->cache->set(
          sprintf('%s.%s', static::CACHE_KEY, $key),
          $value
        );
    }

    private function has(string $key): bool
    {
        return $this->cache->has(sprintf('%s.%s', static::CACHE_KEY, $key));
    }
}
