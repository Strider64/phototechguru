<div class="nav">
    <input type="checkbox" id="nav-check">

    <h3 class="nav-title">&nbsp;</h3>

    <div class="nav-btn">
        <label for="nav-check">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>

    <div class="nav-links">
        <a href="photogallery.php">Home</a>
        <?php
            if (isset($_SESSION['last_login']) && $_SESSION['last_login']) {
                echo '<a href="bpreadings.php">BP</a>';
            } else {
                echo '<a href="display_bp.php">BP</a>';
            }
        ?>

        <a href="game.php">Trivia</a>
        <a href="contact.php">Contact</a>
        <?php
        if (isset($_SESSION['id'])) {
            echo '<a href="/admin/gallery.php">Admin</a>';
            echo '<a href="/admin/logout.php">Logout</a>';
        }
        ?>
    </div>
</div>