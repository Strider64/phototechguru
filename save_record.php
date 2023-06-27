<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\ErrorHandler;
use PhotoTech\Database;
use PhotoTech\TriviaDatabaseOBJ as Trivia;

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();

// Check to see if user is Logged In
if (!$database->check_login_token()) {
    header('location: index.php');
    exit();
}

// Check that the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorOutput('Invalid request method', 400);
    exit();
}

// Parse the input data
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    errorOutput('Invalid input data', 400);
    exit();
}

// Validate the input data
$requiredFields = ['id', 'user_id', 'hidden', 'category', 'question', 'ans1', 'ans2', 'ans3', 'ans4', 'correct'];
foreach ($requiredFields as $field) {
    if (!array_key_exists($field, $data)) {
        errorOutput("Missing required field: $field", 400);
        exit();
    }
}


// Update the database record
try {
    $trivia = new Trivia($pdo, $data);
    $result = $trivia->update();
} catch (\Throwable $e) {
    errorOutput('Database error: ' . $e->getMessage(), 500);
    exit();
}

if ($result) {
    output('Data successfully updated');
} else {
    errorOutput('Failed to update data', 500);
}

function errorOutput($output, $code = 500)
{
    http_response_code($code);
    echo json_encode(['error' => $output]);
}

function output($output)
{
    http_response_code(200);
    echo json_encode(['data' => $output]);
}