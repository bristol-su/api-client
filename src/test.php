<?php

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/httpClient.php');

/** @var \BristolSU\ApiToolkit\Response $result */
$result = $client->control()->group()->getById(1);

var_dump($result->getBody());
