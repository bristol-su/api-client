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
     * @var static
     */
    static $base;

    /**
     * GuzzleHttpClient constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        static::$base = $this;
        $this->client = $client;
    }

    private $headers = [];
    /**
     * @var string
     */

    private $baseUrl;

    private $body = [];

    public function base()
    {
        return static::$base;
    }

    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    public function addBody(array $body): void
    {
        $this->body = array_merge(
          $this->body, $body
        );
    }

    public function addBodyElement(string $key, $element): void
    {
        $this->body[$key] = $element;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }

    public function request(string $method, string $uri)
    {
        var_dump(static::$base, $this);
        die();
        $this->client->request($method, $uri);
    }

}
