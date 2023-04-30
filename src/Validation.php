<?php


namespace PhotoTech;

use PDO;


class Validation extends DatabaseObject
{
    public string $table = "admins"; // Table Name:
    protected PDO $pdo;

    public function __construct(PDO $pdo, array $args = [])
    {
        $this->pdo = $pdo;

        // Caution: allows private/protected properties to be set
        foreach ($args as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
                $this->params[$k] = $v;
                $this->objects[] = $v;
            }
        }
    } // End of construct method:

    public function usernameCheck($username): array
    {



        $query = "SELECT username FROM " . $this->table ." WHERE username = :username";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $data['check'] = true;
            return $data;
        }

        $data['check'] = false;
        return $data;


    }

    public function verifyPassword($password, $redo): bool
    {
        return $password === $redo;
    }
}