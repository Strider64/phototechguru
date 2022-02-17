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
        <a href="photogallery.php">Gallery</a>
        <a href="/admin/index.php">Admin</a>
        <a href="game.php">Quiz</a>
        <a href="contact.php">Contact</a>
        <?php
        if (isset($_SESSION['id'])) {
            echo '<a href="/admin/logout.php">Logout</a>';
        }
        ?>
    </div>
</div>