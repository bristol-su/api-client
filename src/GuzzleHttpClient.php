<?php

namespace BristolSU\ApiClient;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpClient implements \BristolSU\ApiToolkit\Contracts\HttpClient
{

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var \BristolSU\ApiToolkit\Contracts\HttpClientConfig
     */
    private $globalConfig;

    /**
     * @var \BristolSU\ApiToolkit\Contracts\HttpClientConfig
     */
    private $config;

    /**
     * GuzzleHttpClient constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client, \BristolSU\ApiToolkit\Contracts\HttpClientConfig $config)
    {
        $this->client = $client;
        $this->config = $config;
        $this->globalConfig = clone $config;
    }

    public function global(): \BristolSU\ApiToolkit\Contracts\HttpClientConfig
    {
        return $this->globalConfig;
    }

    public function options(): array
    {
        return array_merge(
          $this->globalConfig->toArray(),
          $this->config->toArray(),
          ['verify' => false]
        );
    }

    public function config(): \BristolSU\ApiToolkit\Contracts\HttpClientConfig
    {
        return $this->config;
    }

    public function __call($name, $arguments)
    {
        return $this->config->{$name}($arguments);
    }

    public function request(string $method, string $uri): ResponseInterface
    {
        $response = $this->client->request($method, $uri, $this->options());
        $this->config->clear();
        return $response;
    }

    public function post(string $uri): ResponseInterface
    {
        return $this->request('post', $uri);
    }

    public function get(string $uri): ResponseInterface
    {
        return $this->request('get', $uri);
    }

    public function patch(string $uri): ResponseInterface
    {
        return $this->request('patch', $uri);
    }

    public function delete(string $uri): ResponseInterface
    {
        return $this->request('delete', $uri);
    }

    public function put(string $uri): ResponseInterface
    {
        return $this->request('put', $uri);
    }
}
