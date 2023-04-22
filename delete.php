<?php
// delete.php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\ErrorHandler;
use PhotoTech\Database;

use PhotoTech\ImageContentManager;

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();



// New Instance of Login Class
if (!$database->check_login_token()) {
    header('location: index.php');
    exit();
}

$id = $_GET['id'] ?? null;

if (!empty($id)) {
    $args['id'] = $id;
    $gallery = new ImageContentManager($pdo, $args);
    $data = $gallery->fetch_by_id();
    /*
     * Delete the images from the directories
     */
    unlink($data['image_path']);
    unlink($data['thumb_path']);
    /*
     * Delete the record from the Database Table
     */
    $gallery->delete();
    /*
     * Redirect to the Administrator's Home page
     */
    header("Location: dashboard.php");
    exit();
}

header("Location: dashboard.php");
exit();

