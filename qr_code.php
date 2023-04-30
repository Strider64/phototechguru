<?php
// qr_code.php

require_once "assets/config/config.php";
require_once "vendor/autoload.php";

use PhotoTech\{
    ErrorHandler,
    Database,
};
use PragmaRX\Google2FA\Google2FA;
use chillerlan\QRCode\{QRCode, QROptions};

// Create an ErrorHandler instance
$errorHandler = new ErrorHandler();
// Set the exception handler to use the ErrorHandler instance
set_exception_handler([$errorHandler, 'handleException']);

// Create a Database instance and establish a connection
$database = new Database();
$pdo = $database->createPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Instantiate Google2FA object
    $google2fa = new Google2FA();

    // Generate the secret key
    $secret = $google2fa->generateSecretKey();
    // Generate the QR code URL
    $qrCodeUrl = $google2fa->getQRCodeUrl('Photo Tech Guru Authentication', $email, $secret);

   // Configure the chillerlan/php-qrcode library
    $options = new QROptions([
        'version' => 9,
        'outputType' => QRCode::OUTPUT_MARKUP_SVG,
        'eccLevel' => QRCode::ECC_L,
    ]);

    // Instantiate the chillerlan/php-qrcode object
    $qrCode = new QRCode($options);

    // Generate the QR code SVG markup
    $qrCodeSVG = $qrCode->render($qrCodeUrl);

    // Return the secret key and the QR code SVG markup
    echo json_encode(['secret' => $secret, 'qrCodeSVG' => $qrCodeSVG]);
}
