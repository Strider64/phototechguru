<?php

namespace PhotoTech;

use PDO;
class Measure extends DatabaseObject
{
    protected static string $table = "bp_measurement"; // Table Name:
    static protected array $db_columns = ['id', 'user_id', 'date_taken', 'systolic', 'diastolic', 'pulse', 'miles_walked', 'weight'];

    public int $id;
    public int $user_id;
    public string $date_taken;
    public int $systolic;
    public int $diastolic;
    public int $pulse;
    public float $miles_walked;
    public int $weight;
    public int $sodium;

    /*
    * Construct the data for the bp_measurement;
    */
    public function __construct($args = [])
    {
//        $this->id = $args['id'] ?? null;
//        $this->user_id = $args['user_id'] ?? null;
//        $this->date_taken = $args['date_taken'] ?? null;
//        $this->systolic = $args['systolic'] ?? null;
//        $this->diastolic = $args['diastolic'] ?? null;
//        $this->pulse = $args['pulse'] ?? null;
//        $this->miles_walked = $args['miles_walked'] ?? null;
//        $this->weight = $args['weight'] ?? null;
//        $this->sodium = $args['sodium'] ?? null;

        // Caution: allows private/protected properties to be set

        foreach ($args as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
                static::$params[$k] = $v;
                static::$objects[] = $v;
            }
        }
    } // End of construct method:

    public static function countByUser($user_id)
    {
        static::$searchItem = 'user_id';
        static::$searchValue = $user_id;
        $sql = "SELECT count(id) FROM " . static::$table . " WHERE user_id=:user_id";
        $stmt = Database::pdo()->prepare($sql);

        $stmt->execute([static::$searchItem => static::$searchValue]);
        return $stmt->fetchColumn();

    }

    public static function totalMiles($user_id) {
        static::$searchItem = 'user_id';
        static::$searchValue = $user_id;
        $sql = "SELECT SUM(miles_walked) AS distance FROM " . static::$table . " WHERE user_id=:user_id";
        $stmt = Database::pdo()->prepare($sql);

        $stmt->execute([static::$searchItem => static::$searchValue]);
        $total = $stmt->fetch(PDO::FETCH_ASSOC);
        return $total['distance'];

    }

} // End of Class