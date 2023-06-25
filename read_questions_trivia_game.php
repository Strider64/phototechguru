<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\ErrorHandler;
use PhotoTech\Database;
use PhotoTech\Trivia;

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();

$trivia = new Trivia($pdo);

/*
 * Get Category from the FETCH statement from javascript
 */
$category = htmlspecialchars($_GET['category']);


if (isset($category)) { // Get rid of $api_key if not using:

    $data = $trivia->fetch_data($category); // Fetch the data from the Database Table:

    $finalOutput = []; // Final Output:
    $ansColumns = []; // Answer Columns from Table Array:
    $finished = []; // Finished Results:
    $index = 0; // Index for answers array:
    $indexArray = 0; // Index for database table array:

    /*
     * Put database table in proper array format in order that
     * JSON will work properly.
     */
    foreach ($data as $question_data) {
        if ($question_data['hidden'] === 'yes') {
            continue;
        }
        foreach ($question_data as $key => $value) {

            /*
             * Use Switch Function to convert to the right array format
             * that is based on the key (index) of that array.
             */
            switch ($key) {

                case 'answer1':
                    $ansColumns['answers'][$index] = $value;
                    break;
                case 'answer2':
                    $ansColumns['answers'][$index + 1] = $value;
                    break;
                case 'answer3':
                    $ansColumns['answers'][$index + 2] = $value;
                    break;
                case 'answer4':
                    $ansColumns['answers'][$index + 3] = $value;
                    break;
            }
        } // End of Inner foreach
        $finished = array_merge($question_data, $ansColumns);
        $finalOutput[$indexArray] = $finished;
        $indexArray++;
    } // End of Outer foreach

    output($finalOutput); // Send properly formatted array back to javascript:
}

/*
 * After converting data array to JSON send back to javascript using
 * this function.
 */
function output($output): void
{
    http_response_code(200);
    try {
        echo json_encode($output, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
    }

}