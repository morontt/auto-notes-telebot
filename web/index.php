<?php declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use TeleBot\Kernel;

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');

Request::setTrustedProxies(
    ['127.0.0.1', 'REMOTE_ADDR'],
    Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO
);

$kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
