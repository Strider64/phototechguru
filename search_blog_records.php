<?php
header('Content-Type: application/json');
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\ErrorHandler;
use PhotoTech\Database;
use PhotoTech\LoginRepository as Login;

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();
$login = new Login($pdo);

try {
// The search term you're looking for
    $searchTerm = json_decode(file_get_contents('php://input'), true)['searchTerm'];

// Prepare the SQL query with placeholders for both heading and content
    $sql = "SELECT * FROM gallery WHERE heading LIKE :searchTerm1 OR content LIKE :searchTerm2 LIMIT 1";
    $stmt = $pdo->prepare($sql);

// Bind the search term to both placeholders
    $stmt->bindValue(':searchTerm1', '%' . $searchTerm . '%');
    $stmt->bindValue(':searchTerm2', '%' . $searchTerm . '%');

// Execute the prepared statement
    $stmt->execute();

// Fetch the results and handle them as needed
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($results) {
        echo json_encode($results);
    } else {
        echo json_encode(['message' => 'No results found.']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

