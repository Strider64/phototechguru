<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\Measure;


if (!isset($_SESSION['last_login'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['id'] != 2) {
    header('Location: index.php');
    exit();
}

/* Makes it, so we don't have to decode the json coming from javascript */
header('Content-type: application/json');


$data = json_decode(file_get_contents('php://input'), true);

/*
 * Throw error if something is wrong
 */

function errorOutput($output, $code = 500) {
    http_response_code($code);
    try {
        echo json_encode($output, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
    }
}

/*
 * After converting data array to JSON send back to javascript using
 * this function.
 */
function output($output) {
    http_response_code(200);
    try {
        echo json_encode($output, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
    }
}