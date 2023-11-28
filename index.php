<?php

use Src\Config;
use Src\Database;
use Src\Controller\TransactionController;
use Src\TransactionGateway;

require "vendor/autoload.php";

set_exception_handler("\Src\ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

$uri = explode("/", $_SERVER["REQUEST_URI"]);

if ($uri[1] != 'fatpayapi') {
    http_response_code(404);
    exit;
}

$id = $uri[2] ?? null;

$config = new Config();

$database = new Database($config::HOST, $config::DATABASE, $config::USER, $config::PASSWORD);

$gateway = new TransactionGateway($database);

$controller = new TransactionController($gateway);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);