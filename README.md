# Website Development & Design
Created by John Pepp on February 1, 2021

Updated on April 20, 2024

A responsive website that deals with photography and website development using the latest coding practices.

# Website Development and Photography
I am a photographer, and a website developer that uses no framework or library. I use HTML5, CSS3 and Vanilla JavaScript for the client side using grids/flex to make for a fluid changeable style. I use PHP and Object-Oriented Programming for the server-side, so I can incorporate a fully functional Content Management System (CMS) where the owner or system administrator can change the content with ease. The website will look great on a smartphone, tablet or a PC/Mac computer as I use responsive design. I can also incorporate Ajax (Fetch) that will make for a seamless experience for people using the website. Feel free to check around this website and if interested just use the contact page, and I will get back to you as soon as I can.
 
# New and Improve PHP Obect Oriented Programming
I use Classes, Interfaces and Traits
<pre>
```php
<?php
// NavigationMenu.php

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

```
</pre>
