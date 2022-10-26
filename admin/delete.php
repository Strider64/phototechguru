<?php
require_once "../assets/config/config.php";
require_once "../vendor/autoload.php";
use PhotoTech\CMS;
use PhotoTech\Login;

Login::is_login($_SESSION['last_login']);
Login::securityCheck();

$delete = new CMS();

$id = $_GET['id'] ?? null;

if (!empty($id)) {
    $data = CMS::fetch_by_id($id);
    /*
     * Delete the images from the directories
     */
    unlink("../" . $data['image_path']);
    /*
     * Delete the record from the Database Table
     */
    $delete->delete($id);
    /*
     * Redirect to the Administrator's Home page
     */
    header("Location: index.php");
    exit();
}

header("Location: index.php");
exit();
