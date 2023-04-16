<?php
require_once "../assets/config/config.php";
require_once "../vendor/autoload.php";


use PhotoTech\ErrorHandler;
use PhotoTech\Database;
use PhotoTech\LoginRepository as Login;

$errorHandler = new ErrorHandler();
$database = new Database($errorHandler);

$pdo = $database->createPDO();

$loginRepository = new Login($pdo);

$loginRepository->logoff();