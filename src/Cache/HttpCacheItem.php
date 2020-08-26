<?php

namespace BristolSU\ApiClient\Cache;

use Psr\Http\Message\ResponseInterface;

class HttpCacheItem
{

    private $response;

    private $responseBody = '';

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public static function fromResponse(ResponseInterface $response): HttpCacheItem
    {
        $httpCacheItem = new static();
        $httpCacheItem->setResponse($response);
        return $httpCacheItem;
    }

    public function __sleep()
    {
        if ($this->response !== null) {
            $this->responseBody = (string) $this->response->getBody();
            $this->response->getBody()->rewind();
        }

        return array_keys(get_object_vars($this));
    }

    public function __wakeup()
    {
        if ($this->response !== null) {
            $this->response = $this->response
              ->withBody(
                \GuzzleHttp\Psr7\stream_for($this->responseBody)
              );
        }
    }

}
