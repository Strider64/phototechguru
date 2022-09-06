<?php

require_once 'assets/config/config.php';


$db_options = [
    /* important! use actual prepared statements (default: emulate prepared statements) */
    PDO::ATTR_EMULATE_PREPARES => false
    /* throw exceptions on errors (default: stay silent) */
    , PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    /* fetch associative arrays (default: mixed arrays)    */
    , PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];


$pdo = new PDO('mysql:host=' . DATABASE_HOST . ';dbname=' . DATABASE_NAME . ';charset=utf8', DATABASE_USERNAME, DATABASE_PASSWORD, $db_options);
$stmt = $pdo->prepare('SELECT name FROM demo_cities WHERE state_id = :state_id');
$stmt->execute(['state_id' => $_GET['id']]);
$cities = $stmt->fetchAll(PDO::FETCH_ASSOC);


output($cities);


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
