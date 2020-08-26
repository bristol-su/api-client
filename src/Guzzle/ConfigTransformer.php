<?php

namespace BristolSU\ApiClient\Guzzle;

use BristolSU\ApiToolkit\HttpClientConfig;

class ConfigTransformer
{

    public static function transform(HttpClientConfig $config): array
    {
        $options = [];
        if(count($config->getHeaders()) > 0) {
            $options['headers'] = $config->getHeaders();
        }
        if(count($config->getQuery()) > 0) {
            $options['query'] = $config->getQuery();
        }
        if(count($config->getBody()) > 0) {
            $key = 'body';
            if($config->getHeader('Content-Type') === 'application/json') {
                $key = 'json';
            } elseif($config->getHeader('Content-Type') === 'application/x-www-form-urlencoded') {
                $key = 'form_params';
            } elseif($config->getHeader('Content-Type') === 'multipart/form-data') {
                $key = 'multipart';
            }
            $options[$key] = $config->getBody();
        }
        $options['verify'] = $config->shouldVerifySSL();
        return $options;
    }

}
