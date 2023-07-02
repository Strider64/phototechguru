<?php
// NavigationMenuTrait.php

namespace PhotoTech;

use htmlspecialchars;

trait NavigationMenuTrait
{
    public function regular_navigation(): void
    {
        $navItems = [
            'Home' => 'index.php',
            'Brain Busters' => 'brainbusters.php',
            'Can You See?' => 'hangman/can_you_solve.php',
            'Gallery' => 'gallery.php',
            'Contact' => 'contact.php',
        ];

        foreach ($navItems as $title => $path) {
            $href = $this->generateHref($path);
            $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
            echo "<a href=\"{$href}\">{$safeTitle}</a>";
        }

        // Check for the presence of the cookie
        if (isset($_COOKIE['login_token'])) {
            // Verify the token against the stored value
            $stored_token = $_SESSION['login_token'] ?? '';

            // User is Not logged In
            if (!hash_equals($stored_token, $_COOKIE['login_token'])) {
                $loginHref = $this->generateHref('admin/login.php');
                echo "<a href=\"{$loginHref}\">Login</a>";
            }
        }

        // Add this line for testing purposes
        //echo "<a href=\"https://www.phototechguru.com/admin/login.php\">Test Login</a>";
    }

    private function generateLoginHref(string $current_dir): string
    {
        if ($current_dir == '/admin') {
            $path = 'login.php';
        } else {
            $path = 'admin/login.php';
        }

        return $path;
    }

    public function showAdminNavigation(): void
    {
        $navItems = [
            'Dashboard' => '../dashboard.php',
            'Add Game' => '/hangman/add_question.php',
            'Edit Game' => '/hangman/edit_question.php',
            'Add Blog' => '/create_blog.php',
            'Edit Blog' => '/edit_blog.php',
            'New Quest' => '/new_questions.php',
            'Edit Quest' => '/edit_questions.php',
            'Register' => '/register.php',
            'Logout' => '/admin/logout.php',
        ];

        echo '<div class="admin-navigation">';
        foreach ($navItems as $title => $path) {
            $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
            echo "<a href=\"{$path}\">{$safeTitle}</a>";
        }
        echo '</div>';
    }

    private function generateHref(string $path): string
    {
        $base_url = 'https://' . $_SERVER['HTTP_HOST'];
        return $base_url . '/' . $path;
    }
}
