<?php
require_once '../assets/config/config.php';
require_once "../vendor/autoload.php";

use PhotoTech\ErrorHandler;
use PhotoTech\Database;


$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    errorOutput('Invalid input data', 400);
    exit();
}


// Fetch the word from the database
$stmt = $pdo->prepare('SELECT question FROM bird_trivia WHERE id=:id LIMIT 1');
$stmt->execute(['id' => (int)$data['id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

output(['question' => $result['question']]);

function errorOutput($output, $code = 500): void
{
    http_response_code($code);
    echo json_encode(['error' => $output]);
}

function output($data): void
{
    http_response_code(200);
    echo json_encode($data);
}