<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\{
    ErrorHandler,
    Database,
    Links,
    ImageContentManager
};

$errorHandler = new ErrorHandler();
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();

$args = [];
$gallery = new ImageContentManager($pdo, $args);

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = $_GET['category'];
    $headings = $gallery->fetch_headings_by_category($category);

    header('Content-Type: application/json');
    echo json_encode($headings);
} else {
    http_response_code(400);
    echo "Error: Missing category parameter.";
}
