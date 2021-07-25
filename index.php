<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
/*
 * The Photo Tech Guru
 * Created by John R. Pepp
 * Date Created: July, 12, 2021
 * Last Revision: July 25, 2021
 * Version: 1.50 ßeta
 *
 */
use PhotoTech\CMS;
use PhotoTech\Pagination;

/*
 * Using pagination in order to have a nice looking
 * website page.
 */

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $current_page = urldecode($_GET['page']);
} else {
    $current_page = 1;
}

$per_page = 6; // Total number of records to be displayed:
$total_count = CMS::countAllPage('blog'); // Total Records in the db table:


/* Send the 3 variables to the Pagination class to be processed */
$pagination = new Pagination($current_page, $per_page, $total_count);


/* Grab the offset (page) location from using the offset method */
$offset = $pagination->offset();
//echo "<pre>" . print_r($offset, 1) . "</pre>";
//die();
/*
 * Grab the data from the CMS class method *static*
 * and put the data into an array variable.
 */
$cms = CMS::page($per_page, $offset,'blog');

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>The Photo Tech Guru</title>
    <link rel="stylesheet" media="all" href="assets/css/styles.css">
</head>
<body class="site">
<header class="masthead">

</header>
<?php include_once "assets/includes/inc.nav.php"; ?>
<main class="content">
    <div class="gallery">

        <?php
        foreach ($cms as $record) {
            echo "<article>\n";
            echo '<img src="' . $record['image_path'] . '" alt="testing">';
            echo '<div class="text">';
            echo '<h3>' . $record['heading'] . '</h3>';
            echo '<ul class="cameraData">';
            echo "<li>Shutter Speed " . $record['ExposureTime'] . '</li>';
            echo '<li>Aperture ' . $record['Aperture'] .'</li>';
            echo '<li>' . $record['ISO'] . '</li>';
            echo '<li>Focal Length ' . $record['FocalLength'] . '</li>';
            echo '</ul>';
            echo "<button>" . $record['Model'] . "</button>";
            echo '</div>';
            echo "</article>\n";
        }
        ?>
    </div>
</main>
<aside class="sidebar">
    <?php
    $url = 'index.php';
    echo $pagination->new_page_links($url);
    ?>
</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
</body>
</html>
