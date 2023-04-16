<?php
// ErrorHandlerInterface.php
namespace PhotoTech;

use Throwable;

interface ErrorHandlerInterface {
    public function handleException(Throwable $e): void;
}

