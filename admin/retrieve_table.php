<?php

require_once '../assets/config/config.php';
require_once "../vendor/autoload.php";

use PhotoTech\LoginRepository;
use PhotoTech\Trivia;

LoginRepository::is_login($_SESSION['last_login']);


$data = Trivia::fetch_all_categories();

/* Makes it, so we don't have to decode the json coming from javascript */
header('Content-type: application/json');


output($data);


/*
 * After converting data array to JSON send back to javascript using
 * this function.
 */
function output($output)
{
    http_response_code(200);
    try {
        echo json_encode($output, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
    }
}
