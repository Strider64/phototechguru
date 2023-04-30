<?php
require_once "assets/config/config.php";
require_once "vendor/autoload.php";

use Intervention\Image\ImageManagerStatic as Image;
use PhotoTech\{
    ErrorHandler,
    Database,
    Validation,
    Links,
    ImageContentManager
};

// Create an ErrorHandler instance
$errorHandler = new ErrorHandler();
// Set the exception handler to use the ErrorHandler instance
set_exception_handler([$errorHandler, 'handleException']);

// Create a Database instance and establish a connection
$database = new Database();

// Check to see if user is Logged In
if (!$database->check_login_token()) {
    header('location: index.php');
    exit();
}

$pdo = $database->createPDO();

$validation = new Validation($pdo);

/*
 * The below must be used in order for the json to be decoded properly.
 */
$data = json_decode(file_get_contents('php://input'), true);

$result = $validation->usernameCheck($data['check']);

output($result);




function errorOutput($output, $code = 500) {
    http_response_code($code);
    echo json_encode($output);
}


/*
 * After converting data array to JSON send back to javascript using
 * this function.
 */
function output($output) {
    http_response_code(200);
    echo json_encode($output);
}
