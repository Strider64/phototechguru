<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\sendMail;

/*
 * The below must be used in order for the json to be decoded properly.
 */
$data = json_decode(file_get_contents('php://input'), true);
$data['message'] =
    '<html lang="en">' .
    '<body style=\'background: #eee;\'>' .
    '<p style="font-size: 1.8em; line-height: 1.5;">' . $data['name'] . '<br>' . $data['email'] . '<br>' . $data['phone'] . '<br>' . $data['website'] . '<br> Subject of Email is ' . $data['reason'] . '</p>' .
    '<p style="font-size: 1.6em; line-height: 1.5;">' . $data['comments'] . '</p>' .
    '<p style="font-size: 1.4em; line-height: 1.5;">Thank You, for taking your time to contact me! I will get back to you as soon as I can.<br> Best regards, John Pepp</p>' .
    '</body>' .
    '</html>';
$send = new sendMail();
$result = $send->sendEmail($data);

/* Send Back Result (true or false) back to Fetch */
if ($result) {
    output(true);
} else {
    errorOutput(false);
}


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