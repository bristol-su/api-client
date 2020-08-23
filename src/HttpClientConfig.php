<?php


namespace BristolSU\ApiClient;


class HttpClientConfig implements \BristolSU\ApiToolkit\Contracts\HttpClientConfig
{

    private $headers = [];
    /**
     * @var string
     */

    private $body = [];

    private $contentType = 'application/json';

    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
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

    public function toArray(): array
    {
        $array = [];
        if(count($this->headers) > 0) {
            $array['headers'] = $this->headers;
        }
        if(count($this->body) > 0) {
            $key = 'body';
            if($this->contentType = 'application/json') {
                $key = 'json';
            } elseif($this->contentType === 'application/x-www-form-urlencoded') {
                $key = 'form_params';
            } elseif($this->contentType === 'multipart/form-data') {
                $key = 'multipart';
            }
            $array[$key] = $this->body;
        }
        return $array;
    }

    public function clear(): void
    {
        $this->headers = [];
        $this->body = [];
    }
}
