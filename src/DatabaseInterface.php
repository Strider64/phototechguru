<?php
// DatabaseInterface.php
namespace PhotoTech;

use PDO;

interface DatabaseInterface {
    public function createPDO(): ?PDO;
}
