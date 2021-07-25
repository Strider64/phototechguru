<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\Trivia;

$trivia = new Trivia();

try {
    $todays_data = new DateTime("now", new DateTimeZone("America/Detroit"));
} catch (Exception $e) {
}


/* Makes it so we don't have to decode the json coming from javascript */
header('Content-type: application/json');

/*
 * The below must be used in order for the json to be decoded properly.
 */
try {
    $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
}
try {
    $todays_data = new DateTime('now', new DateTimeZone("America/Detroit"));
} catch (Exception $e) {
}
$data['day_of_year'] = $todays_data->format('z');

$result = $trivia::insertHighScores($data);


if ($result) {

    output(true);
}

/*
 * Throw error if something is wrong
 */

/**
 * @throws JsonException
 */
function errorOutput($output, $code = 500) {
    http_response_code($code);
    echo json_encode($output, JSON_THROW_ON_ERROR);
}

/*
 * If everything validates OK then send success message to Ajax / JavaScript
 */

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