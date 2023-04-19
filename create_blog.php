<?php
require_once "assets/config/config.php";
require_once "vendor/autoload.php";

use PhotoTech\ErrorHandler;
use PhotoTech\Database;
use PhotoTech\LoginRepository as Login;
use PhotoTech\Gallery;

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();
$login = new Login($pdo);
if (!$login->check_login_token()) {
    header('location: index.php');
    exit();
}

$save_result = false;

if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_FILES['image'])) {
    $data = $_POST['gallery'];
    $errors = array();
    $exif_data = [];
    $file_name = $_FILES['image']['name']; // Temporary file for thumbnails directory:
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $thumb_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    /*
     * Set EXIF data info of image for database table that is
     * if it contains the info otherwise set to null.
     */
    if ($file_ext === 'jpeg' || $file_ext === 'jpg') {

        $exif_data = exif_read_data($file_tmp);


        if (array_key_exists('Make', $exif_data) && array_key_exists('Model', $exif_data)) {
            $data['Model'] = $exif_data['Make'] . ' ' . $exif_data['Model'];
        }

        if (array_key_exists('ExposureTime', $exif_data)) {
            $data['ExposureTime'] = $exif_data['ExposureTime'] . "s";
        }

        if (array_key_exists('ApertureFNumber', $exif_data['COMPUTED'])) {
            $data['Aperture'] = $exif_data['COMPUTED']['ApertureFNumber'];
        }

        if (array_key_exists('ISOSpeedRatings', $exif_data)) {
            $data['ISO'] = "ISO " . $exif_data['ISOSpeedRatings'];
        }

        if (array_key_exists('FocalLengthIn35mmFilm', $exif_data)) {
            $data['FocalLength'] = $exif_data['FocalLengthIn35mmFilm'] . "mm";
        }

    } else {
        $data['Model'] = null;
        $data['ExposureTime'] = null;
        $data['Aperture'] = null;
        $data['ISO'] = null;
        $data['FocalLength'] = null;
    }

    $data['content'] = trim($data['content']);

    $extensions = array("jpeg", "jpg", "png");

    if (in_array($file_ext, $extensions, true) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }

    if ($file_size >= 58720256) {
        $errors[] = 'File size must be less than or equal to 42 MB';
    }

    /*
     * Create unique name for image.
     */
    $image_path = 'assets/image_path/img-gallery-' . time() . '-2048x1365' . '.' . $file_ext;
    $thumb_path = 'assets/thumb_path/thumb-gallery-' . time() . '-205x137' . '.' . $file_ext;

    move_uploaded_file($file_tmp, $image_path);
    move_uploaded_file($thumb_tmp, $thumb_path);

// Load the image
    $image = imagecreatefromjpeg($image_path);

// Get the original dimensions
    $original_width = imagesx($image);
    $original_height = imagesy($image);

// Calculate the new dimensions
    $new_width = 2048;
    $new_height = 1365;
    $scale = min($new_width / $original_width, $new_height / $original_height);
    $width = intval($original_width * $scale);
    $height = intval($original_height * $scale);

// Create a new image with the new dimensions
    $new_image = imagecreatetruecolor($width, $height);

// Copy and resize the original image to the new image
    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, $original_width, $original_height);

// Save the new image
    imagejpeg($new_image, $image_path, 100);

// Create a thumbnail
    $new_width = 205;
    $new_height = 137;
    $scale = min($new_width / $original_width, $new_height / $original_height);
    $width = intval($original_width * $scale);
    $height = intval($original_height * $scale);
    $thumb_image = imagecreatetruecolor($width, $height);
    imagecopyresampled($thumb_image, $image, 0, 0, 0, 0, $width, $height, $original_width, $original_height);
    imagejpeg($thumb_image, $thumb_path, 100);

// Clean up
    imagedestroy($image);
    imagedestroy($new_image);
    imagedestroy($thumb_image);



    $today = $todayDate = new DateTime('today', new DateTimeZone("America/Detroit"));


    $data['date_updated'] = $data['date_added'] = $today->format("Y-m-d H:i:s");

    $data['image_path'] = $image_path;
    $data['thumb_path'] = $thumb_path;


    /*
     * If no errors save ALL the information to the
     * database table.
     */
    if (empty($errors) === true) {
        /* Save to Database Table CMS */

        $today = $todayDate = new DateTime('today', new DateTimeZone("America/Detroit"));

        $data['date_updated'] = $data['date_added'] = $today->format("Y-m-d H:i:s");
        $gallery = new Gallery($pdo, $data);
        $result = $gallery->create();
        if ($result) {
            header("Location: dashboard.php");
            exit();
        }
    } else {
        return $errors;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Insert Images</title>
    <link rel="stylesheet" media="all" href="assets/css/stylesheet.css">
</head>
<body class="site">
<header class="headerStyle">

</header>
<div class="nav">

    <input type="checkbox" id="nav-check">


    <div class="nav-btn">
        <label for="nav-check">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>

    <div class="nav-links">
        <?php $login->show_logoff_nav_button(); ?>
    </div>

    <div class="name-website">
        <h1 class="webtitle">The Photo Tech Guru</h1>
    </div>

</div>
<div class="main_container">
    <form id="data_entry_form" class="checkStyle" action="create_blog.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="gallery[user_id]" value="<?= $_SESSION['user_id'] ?>">
        <input type="hidden" name="gallery[author]" value="John Pepp>">
        <input type="hidden" name="gallery[page]" value="gallery">
        <input type="hidden" name="action" value="upload">
        <div id="file_grid_area">
            <input id="file" class="file-input-style" type="file" name="image">
            <label for="file">Select file</label>
        </div>
        <label id="select_grid_category_area">
            <select class="select-css" name="gallery[category]">
                <option disabled>Select a Category</option>
                <option value="general">General</option>
                <option value="lego">LEGO</option>
                <option value="halloween">Halloween</option>
                <option value="landscape">Landscape</option>
                <option selected value="wildlife">Wildlife</option>
            </select>
        </label>
        <div id="heading_heading_grid_area">
            <label class="heading_label_style" for="heading">Heading</label>
            <input class="enter_input_style" id="heading" type="text" name="gallery[heading]" value="" tabindex="1" required
                   autofocus>
        </div>
        <div id="content_style_grid_area">
            <label class="text_label_style" for="content">Content</label>
            <textarea class="text_input_style" id="content" name="gallery[content]" tabindex="2"></textarea>
        </div>
        <div id="submit_picture_grid_area">
            <button class="form-button" type="submit" name="submit" value="enter">submit</button>
        </div>
    </form>
</div>
<aside class="sidebar">

</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
</body>
</html>
