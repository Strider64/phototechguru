<?php
require_once "../assets/config/config.php";
require_once "../vendor/autoload.php";

use PhotoTech\Trivia;
use PhotoTech\LoginRepository;

LoginRepository::is_login($_SESSION['last_login']);
$user = LoginRepository::securityCheck();

$delete = new Trivia();

$id = $_GET['id'] ?? null;

if (!empty($id)) {
    $data = Trivia::fetch_by_id($id);

    /*
     * Delete the record from the Database Table
     */
    $delete->delete($id);
    /*
     * Redirect to the Administrator's Home page
     */
    header("Location: editQuiz.php");
    exit();
}

header("Location: editQuiz.php");
exit();
