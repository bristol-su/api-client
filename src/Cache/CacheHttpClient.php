<?php

namespace BristolSU\ApiClient\Cache;

use BristolSU\ApiToolkit\Contracts\HttpClient;
use BristolSU\ApiToolkit\HttpClientConfig;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Tests\Psr7\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\SimpleCache\CacheInterface;
use function GuzzleHttp\Psr7\stream_for;

class CacheHttpClient implements HttpClient
{

    const CACHE_KEY = 'BristolSU.ApiClient.CacheHttpClient.request';

    const CACHE_FOR = 3600;

    /**
     * @var HttpClient
     */
    private $client;
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(HttpClient $client, CacheInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    public function global(): \BristolSU\ApiToolkit\HttpClientConfig
    {
        return $this->client->global();
    }

    public function config(): \BristolSU\ApiToolkit\HttpClientConfig
    {
        return $this->client->config();
    }

    public function options(): array
    {
        return $this->client->options();
    }

    public function request(string $method, string $uri): ResponseInterface
    {
        if(!$this->mergedConfig()->shouldCache()) {
            return $this->client->request($method, $uri);
        }
        $key = $this->cacheKey($method, $uri);
        if($this->cache->has($key)) {
            return ($this->cache->get($key))->getResponse();
        }
        $response = $this->client->request($method, $uri);

        $this->cache->set($key, HttpCacheItem::fromResponse($response), static::CACHE_FOR);
        return $response;
    }

    public function post(string $uri): ResponseInterface
    {
        return $this->client->post($uri);
    }

    public function get(string $uri): ResponseInterface
    {
        return $this->request('GET', $uri);
    }

    public function patch(string $uri): ResponseInterface
    {
        return $this->client->patch($uri);
    }

    public function delete(string $uri): ResponseInterface
    {
        return $this->client->delete($uri);
    }

    public function put(string $uri): ResponseInterface
    {
        return $this->client->put($uri);
    }

    private function cacheKey(string $method, string $uri)
    {
        return sprintf('%s.%s.%s.%s',
          static::CACHE_KEY,
          $method,
          str_replace('/', '_', $uri),
          str_replace([
            '[', ']', '{', '}', '/', '\\', '@', ':'
          ], [
            '_sqopen_', '_sqclose_', '_curopen_', '_curclose_', '_forslash_', '_backslash_', '_at_', '_colon_'
          ], json_encode($this->mergedConfig()->getQuery()))
        );
    }

    public function mergedConfig(): HttpClientConfig
    {
        return $this->global()->merge($this->config());
    }
}
