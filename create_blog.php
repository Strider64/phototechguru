<?php
require_once "assets/config/config.php";
require_once "vendor/autoload.php";

use Intervention\Image\ImageManagerStatic as Image;
use PhotoTech\{
    ErrorHandler,
    Database,
    LoginRepository as Login,
    ImageContentManager
};

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

function is_ajax_request(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}


$save_result = false;

if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_FILES['image'])) {
    $data = $_POST['gallery'];
    $errors = array();
    $exif_data = [];
    $file_name = $_FILES['image']['name']; // Temporary file:
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
    $image_random_string = bin2hex(random_bytes(16));
    $image_path = 'assets/image_path/img-gallery-' . $image_random_string . '-2048x1365' . '.' . $file_ext;
    $thumb_path = 'assets/thumb_path/thumb-gallery-' . $image_random_string . '-205x137' . '.' . $file_ext;


    move_uploaded_file($file_tmp, $image_path);
    move_uploaded_file($thumb_tmp, $thumb_path);


    // Load the image
    $image = Image::make($image_path);

    // Resize the image
    $image->resize(2048, 1365, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });

   // Save the new image
    $image->save($image_path, 100);

    // Load the image with Intervention Image
    $image = Image::make($image_path);

    // Resize the image while maintaining the aspect ratio
    $image->resize(205, 137, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });

    // Save the thumbnail
    $image->save($thumb_path, 100);


    $data['image_path'] = $image_path;
    $data['thumb_path'] = $thumb_path;


    /*
     * If no errors save ALL the information to the
     * database table.
     */
    if (empty($errors) === true) {
        // Save to Database Table CMS
        $timezone = new DateTimeZone('America/Detroit'); // Use your timezone here
        $today = new DateTime('now', $timezone);
        $data['date_updated'] = $data['date_added'] = $today->format("Y-m-d H:i:s");
        $gallery = new ImageContentManager($pdo, $data);
        $result = $gallery->create();

        if ($result) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
            exit();
        }
    } else {
        if (is_ajax_request()) {
            // Send a JSON response with errors for AJAX requests
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'errors' => $errors]);
        }
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
        <?php $database->regular_navigation(); ?>
    </div>

    <div class="name-website">
        <h1 class="webtitle">The Photo Tech Guru</h1>
    </div>

</div>
<div class="main_container">
    <div class="home_article">
        <form id="data_entry_form" class="checkStyle" action="create_blog.php" method="post"
              enctype="multipart/form-data">
            <input type="hidden" name="gallery[user_id]" value="<?= $_SESSION['user_id'] ?>">
            <input type="hidden" name="gallery[author]" value="John Pepp>">
            <input type="hidden" name="gallery[page]" value="gallery">
            <input type="hidden" name="action" value="upload">
            <div id="progress_bar_container" class="progress-bar-container">
                <h2 id="progress_bar_title" class="progress-bar-title">Upload Progress</h2>
                <div id="progress_bar" class="progress-bar"></div>
            </div>
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
                <input class="enter_input_style" id="heading" type="text" name="gallery[heading]" value="" tabindex="1"
                       required
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
    <div class="home_sidebar">
        <?php $database->showAdminNavigation(); ?>
    </div>

</div>
<aside class="sidebar">

</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
<script src="assets/js/upload_form_with_progress_bar.js"></script>
</body>
</html>
