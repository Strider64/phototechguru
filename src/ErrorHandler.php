<?php
// ErrorHandler.php
namespace PhotoTech;

use Exception;
use PDOException;
use Throwable;

class ErrorHandler implements ErrorHandlerInterface
{
    public function handleException(Throwable $e): void
    {
        if ($e instanceof PDOException) {
            error_log('PDO Error: ' . $e->getMessage());
        } else {
            error_log('General Error: ' . $e->getMessage());
        }
    }
}