<?php

use Symfony\Component\HttpFoundation\Request;
use TeleBot\Kernel;

require __DIR__ . '/../vendor/autoload.php';

$kernel = new Kernel('dev', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
