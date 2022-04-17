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
        <a href="index.php">Home</a>
        <?php echo($_SESSION['last_login']) ? '<a href="bpreadings.php">BP</a>' : null; ?>
        <a href="photogallery.php">Gallery</a>
        <a href="/admin/index.php">Admin</a>
        <a href="game.php">Trivia</a>
        <a href="contact.php">Contact</a>
        <?php
        if (isset($_SESSION['id'])) {
            echo '<a href="/admin/logout.php">Logout</a>';
        }
        ?>
    </div>
</div>