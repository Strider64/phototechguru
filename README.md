# Website Development & Design
Created by John Pepp on February 1, 2021

Updated on April 20, 2024

A responsive website that deals with photography and website development using the latest coding practices.

# Website Development and Photography
As a photographer and web developer, I specialize in creating custom websites without relying on frameworks or libraries. Utilizing HTML5, CSS3, and Vanilla JavaScript on the client side, I employ grid and flex techniques to achieve a fluid, adaptable design. On the server side, I leverage PHP and Object-Oriented Programming to seamlessly integrate a comprehensive Content Management System (CMS), allowing owners or administrators to effortlessly modify content.

My websites are crafted with responsive design principles, ensuring an exceptional user experience on smartphones, tablets, or desktop computers. By incorporating Ajax (Fetch), I provide a seamless browsing experience for visitors. Feel free to explore this website, and if you're interested in collaborating, simply reach out via the contact page. I'll respond as soon as possible.

# New and Improve PHP Obect Oriented Programming
I use Classes, Interfaces and Traits
<pre>
```php
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

    // More code...
}

```
</pre>
