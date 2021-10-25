<?php


namespace PhotoTech;

use mysql_xdevapi\Exception;
use PDO;
use PDOException;

class Database {

    private PDO $_connection;
    // Store the single instance.
    private static ?Database $_instance = null; // Don't initialize before it is called:

    // Get an instance of the Database.
    // @return Database:
    protected static function getInstance(): Database
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function pdo(): PDO
    {
        $db = static::getInstance();
        return $db->getConnection();
    }

    // Constructor - Build the PDO Connection:
    public function __construct() {
        try {
        $db_options = [
            /* important! use actual prepared statements (default: emulate prepared statements) */
            PDO::ATTR_EMULATE_PREPARES => false
            /* throw exceptions on errors (default: stay silent) */
        , PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            /* fetch associative arrays (default: mixed arrays)    */
        , PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        $this->_connection = new PDO('mysql:host=' . DATABASE_HOST . ';dbname=' . DATABASE_NAME . ';charset=utf8', DATABASE_USERNAME, DATABASE_PASSWORD, $db_options);
        } catch (PDOException $e) {
            //echo $e;
            //echo "<pre>" . print_r($e->errorInfo, 1) . "</pre>";
            if ($e->errorInfo[1] === 1045) {
                echo "Can't Connect to Database " . $e->errorInfo[1] . "<br>";
                return false;
            }

            throw $e; // If PDO Exception error can't handle it throw it to Exception:

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n"; // Not for a production server:
        }
        return true;
    }

    // Empty clone magic method to prevent duplication:
    private function __clone() {

    }

    // Get the PDO connection:
    protected function getConnection(): PDO
    {
        return $this->_connection;
    }

}