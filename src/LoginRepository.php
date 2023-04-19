<?php

namespace PhotoTech;

use JetBrains\PhpStorm\NoReturn;
use PDO;


class LoginRepository implements LoginRepositoryInterface
{

    private string $table = 'admins'; // Replace with your actual table name

    protected PDO $pdo;


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function verify_credentials($username, $password): bool
    {
        $sql = "SELECT id, password FROM " . $this->table . " WHERE username =:username LIMIT 1";
        $user = $this->retrieve_credentials($sql, $username);
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(); // prevent session fixation attacks
            $_SESSION['user_id'] = $user['id'];
            return true;
        }

        return false;
    }


    protected function retrieve_credentials(string $sql, string $username): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }

    public function store_token_in_database($user_id, $token): bool
    {
        $sql = "UPDATE " . $this->table . " SET token=:token WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['token' => $token, 'id' => $user_id]);
        return true;
    }

    public function show_logoff_nav_button(): void
    {
        // Get the current script's directory
        $current_dir = dirname($_SERVER['SCRIPT_NAME']);

        // Check for the presence of the cookie
        if (isset($_COOKIE['login_token'])) {
            // Verify the token against the stored value
            $stored_token = $_SESSION['login_token'] ?? '';

            // User is logged In
            if (hash_equals($stored_token, $_COOKIE['login_token'])) {
                echo '<a href="/hangman/can_you_solve.php">Can You See?</a>';

                if ($current_dir == '/hangman' || $current_dir == '/admin') {
                    echo '<a href="../gallery.php">Gallery</a>';
                    echo '<a href="../contact.php">Contact</a>';
                } else {
                    echo '<a href="gallery.php">Gallery</a>';
                    echo '<a href="contact.php">Contact</a>';
                }

                echo '<a href="../dashboard.php">Dashboard</a>';
                echo '<a href="/hangman/add_question.php">Add Q</a>';
                echo '<a href="/hangman/edit_question.php">Edit Q</a>';
                echo '<a href="/create_blog.php">Create B</a>';
                echo '<a href="/edit_blog.php">Edit B</a>';
                echo '<a href="/admin/logout.php">Logout</a>';
            } else {
                echo '<a href="../index.php">Home</a>';
                echo '<a href="/hangman/can_you_solve.php">Can You See?</a>';

                if ($current_dir == '/hangman') {
                    echo '<a href="../gallery.php">Gallery</a>';
                    echo '<a href="../contact.php">Contact</a>';
                } else {
                    echo '<a href="gallery.php">Gallery</a>';
                    echo '<a href="contact.php">Contact</a>';
                }

                echo '<a href="/admin/login.php">Login</a>';
            }
        } else {
            echo '<a href="../index.php">Home</a>';
            echo '<a href="/hangman/can_you_solve.php">Can You See?</a>';

            if ($current_dir == '/hangman') {
                echo '<a href="../gallery.php">Gallery</a>';
                echo '<a href="../contact.php">Contact</a>';
            } else {
                echo '<a href="gallery.php">Gallery</a>';
                echo '<a href="contact.php">Contact</a>';
            }

            echo '<a href="/admin/login.php">Login</a>';
        }
    }





    public function check_login_token(): bool
    {
        // Check for the presence of the cookie and the session key
        if (isset($_COOKIE['login_token']) && isset($_SESSION['login_token'])) {
            // Verify the token against the stored value
            if ($_COOKIE['login_token'] === $_SESSION['login_token']) {
                return true;
            }
        }

        return false;
    }


    // Logout Method
    #[NoReturn] public function logoff(): void
    {
        // Delete the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Remove the token from the database (assuming you are storing it in the 'token' column of the 'admins' table)
        $sql = "UPDATE " . $this->table . " SET token=NULL WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $_SESSION['user_id']]);
        // Unset all the session variables
        $_SESSION = [];
        // Destroy the session
        session_destroy();

        // Redirect the user to the login page or home page
        header('Location: /admin/login.php');
        exit;
    }


}