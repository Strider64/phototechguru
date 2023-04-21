<?php

namespace PhotoTech;

trait NavigationMenu
{
    public function regular_navigation(): void
    {
        $current_dir = dirname($_SERVER['SCRIPT_NAME']);

        $navItems = [
            'Home' => 'index.php',
            'Can You See?' => 'hangman/can_you_solve.php',
            'Gallery' => 'gallery.php',
            'Contact' => 'contact.php',
        ];

        foreach ($navItems as $title => $path) {
            $href = $this->generateHref($current_dir, $path);
            echo "<a href=\"{$href}\">{$title}</a>";
        }

        // Check for the presence of the cookie
        if (isset($_COOKIE['login_token'])) {
            // Verify the token against the stored value
            $stored_token = $_SESSION['login_token'] ?? '';

            // User is Not logged In
            if (!hash_equals($stored_token, $_COOKIE['login_token'])) {
                echo '<a href="/admin/login.php">Login</a>';
            }
        }
    }

    public function showAdminNavigation(): void
    {
        $navItems = [
            'Dashboard' => '../dashboard.php',
            'Add Game' => '/hangman/add_question.php',
            'Edit Game' => '/hangman/edit_question.php',
            'Add Blog' => '/create_blog.php',
            'Edit Blog' => '/edit_blog.php',
            'Logout' => '/admin/logout.php',
        ];

        echo '<div class="admin-navigation">';
        foreach ($navItems as $title => $path) {
            echo "<a href=\"{$path}\">{$title}</a>";
        }
        echo '</div>';
    }

    private function generateHref(string $current_dir, string $path): string
    {
        if ($current_dir == '/hangman') {
            $path = '../' . $path;
        }

        return $path;
    }
}
