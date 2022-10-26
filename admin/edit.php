<?php
require_once '../assets/config/config.php';
require_once "../vendor/autoload.php";

use PhotoTech\Resize;
use PhotoTech\CMS;
use PhotoTech\Login;

Login::is_login($_SESSION['last_login']);

Login::securityCheck();


$result = false;
$id = (int)htmlspecialchars($_GET['id'] ?? null);
try {
    $today = $todayDate = new DateTime('today', new DateTimeZone("America/Detroit"));
} catch (Exception $e) {
}
$date_updated = $today->format("Y-m-d H:i:s");
/*
 * Set the class to of the record (data) to be display
 * to the class then fetch the data to the $record
 * ARRAY do be displayed on the website. If an
 * update has been done then update database
 * table otherwise just fetch the record
 * by id.
 */
if (isset($_POST['submit'])) {

    $cms = new CMS($_POST['cms']);


    /*
     * If there are no errors then update the image,
     * otherwise just update the information.
     */
    if ($_FILES['image']['error'] === 0) {

        unlink("../" . $_SESSION['old_image']); // Remove old image path from db table:

        $errors = array();
        $exif_data = [];
        $file_name = $_FILES['image']['name']; // Temporary file for thumbnails directory:
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        /*
         * Set EXIF data info of image for database table that is
         * if it contains the info otherwise set to null.
         */
        if ($file_ext === 'jpeg' || $file_ext === 'jpg') {
            /*
             * I don't like suppressing errors, but this
             * is the only way that I can so
             * until find out how to do it this will
             * have to do.
             */
            $exif_data = @exif_read_data($file_tmp);

            if (array_key_exists('Make', $exif_data) && array_key_exists('Model', $exif_data)) {
                $newCMS['Model'] = $exif_data['Make'] . ' ' . $exif_data['Model'];
            }

            if (array_key_exists('ExposureTime', $exif_data)) {
                $newCMS['ExposureTime'] = $exif_data['ExposureTime'] . "s";
            }

            if (array_key_exists('ApertureFNumber', $exif_data['COMPUTED'])) {
                $newCMS['Aperture'] = $exif_data['COMPUTED']['ApertureFNumber'];
            }

            if (array_key_exists('ISOSpeedRatings', $exif_data)) {
                $newCMS['ISO'] = "ISO " . $exif_data['ISOSpeedRatings'];
            }

            if (array_key_exists('FocalLengthIn35mmFilm', $exif_data)) {
                $newCMS['FocalLength'] = $exif_data['FocalLengthIn35mmFilm'] . "mm";
            }

        } else {
            $newCMS['Model'] = null;
            $newCMS['ExposureTime'] = null;
            $newCMS['Aperture'] = null;
            $newCMS['ISO'] = null;
            $newCMS['FocalLength'] = null;
        }


        $extensions = array("jpeg", "jpg", "png");

        if (in_array($file_ext, $extensions, true) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size >= 44040192) {
            $errors[] = 'File size must be less than or equal to 42 MB';
        }

        /*
        * Set the paths to the correct folders
        */
        $dir_path = 'assets/uploads/' . $data['category'] . '/';

        /*
         * Create unique name for image.
         */
        $new_file_name = $dir_path . 'img-gallery-' . time() . '-2048x1365' . '-' . $data['category']. '.' . $file_ext;

        move_uploaded_file($file_tmp, "../" . $new_file_name);

        //$resize = new Resize("../" . $new_file_name);
        //$resize->resizeImage(IMAGE_WIDTH, IMAGE_HEIGHT, 'auto');
        //resize->saveImage("../" . $new_file_name, 100);
        /*
         * Set path information for database table.
         */
        $newCMS['image_path'] = $new_file_name;

        $updateImagePath = new CMS($newCMS);
        $result = $updateImagePath->update();
    } else {
        $result = $cms->update();
    }

    if ($result) {
        header("Location: ../index.php");
        exit();
    }
} elseif ($id && is_int($id)) {
    $record = CMS::fetch_by_id($id);
    $cms = new CMS($record);
    $_SESSION['old_image'] = $cms->image_path;
} else {
    header("Location: gallery.php");
    exit();
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Edit Page</title>
    <link rel="stylesheet" media="all" href="../assets/css/admin.css">
</head>
<body class="site">

<div class="nav">
    <input type="checkbox" id="nav-check">

    <h3 class="nav-title">
        The Photo Tech Guru
    </h3>

    <div class="nav-btn">
        <label for="nav-check">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>

    <div class="nav-links">
        <a href="index.php">home</a>
        <a href="create.php">create</a>
        <a href="../addQuiz.php">Add Questions</a>
        <a href="logout.php">logout</a>
    </div>
</div>

<main id="content" class="checkStyle">
    <form id="formData" class="form_classes" action="edit.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="cms[id]" value="<?= $id ?>">
        <input type="hidden" name="cms[user_id]" value="<?= $_SESSION['id'] ?>">
        <input type="hidden" name="cms[author]" value="<?= Login::full_name() ?>">
        <input type="hidden" name="cms[date_updated]" value="<?= $date_updated ?>">
        <input type="hidden" name="action" value="upload">
        <input type="hidden" name="cms[image_path_name]" value="<?= $cms->image_path ?>">
        <img src="<?= "../" . $_SESSION['old_image'] ?>" alt="current image">
        <br>
        <div class="file-style">
            <input id="file" class="file-input-style" type="file" name="image" value="<?= $cms->image_path ?>">
            <label for="file">Select file</label>
        </div>
        <div class="category-style">
            <select id="category" class="select-css" name="cms[category]" tabindex="1">
                <option selected disabled>Select a Category</option>
                <option value="general" selected>General</option>
                <option value="halloween">Halloween</option>
                <option value="landscape">Landscape</option>
                <option value="wildlife">Wildlife</option>
            </select>
        </div>
        <div class="heading-style">
            <label class="heading_label_style" for="heading">Heading</label>
            <input class="enter_input_style" id="heading" type="text" name="cms[heading]" value="<?= $cms->heading ?>"
                   tabindex="1" required autofocus>
        </div>
        <div class="content-style">
            <label class="text_label_style" for="content">Content</label>
            <textarea class="text_input_style" id="content" name="cms[content]"
                      tabindex="2"><?= $cms->content ?></textarea>
        </div>
        <div class="submit-button">
            <button class="form-button" type="submit" name="submit" value="enter">submit</button>
            <button class="form-button" formaction="delete.php?id=<?= $id ?>"
                    onclick="return confirm('Are you sure you want to delete this item?');">Delete
            </button>
        </div>
    </form>
</main>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
</body>
</html>
