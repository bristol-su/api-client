<?php

namespace BristolSU\ApiClient\Guzzle;

use BristolSU\ApiToolkit\HttpClientConfig;
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
     * @var HttpClientConfig
     */
    private $globalConfig;

    /**
     * @var HttpClientConfig
     */
    private $config;

    /**
     * GuzzleHttpClient constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->config = new HttpClientConfig;
        $this->globalConfig = new HttpClientConfig;
    }

    public function global(): \BristolSU\ApiToolkit\HttpClientConfig
    {
        return $this->globalConfig;
    }

    public function options(): array
    {
        return ConfigTransformer::transform(
          $this->mergedConfig()
        );
    }

    public function config(): \BristolSU\ApiToolkit\HttpClientConfig
    {
        return $this->config;
    }

    public function mergedConfig(): HttpClientConfig
    {
        return $this->global()->merge(
          $this->config()
        );
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
