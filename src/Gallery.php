<?php /** @noinspection ALL */

namespace PhotoTech;

use PDO;
use Exception;
use JetBrains\PhpStorm\Pure;
use DateTime;
use DateTimeZone;

class Gallery implements GalleryInterface
{
    protected string $table = "gallery"; // Table Name:
    protected $db_columns = ['id', 'category', 'user_id', 'thumb_path', 'image_path', 'Model', 'ExposureTime', 'Aperture', 'ISO', 'FocalLength', 'author', 'heading', 'content', 'data_updated', 'date_added'];
    public $id;
    public $user_id;
    public $page;
    public $category;
    public $thumb_path;
    public $image_path;
    public $Model;
    public $ExposureTime;
    public $Aperture;
    public $ISO;
    public $FocalLength;
    public $author;
    public $heading;
    public $content;
    public $date_updated;
    public $date_added;

    protected PDO $pdo;

    public function __construct(PDO $pdo, array $args = [])
    {
        $this->pdo = $pdo;

        // Caution: allows private/protected properties to be set
        foreach ($args as $k => $v) {
            if (property_exists($this, $k)) {
                $v = $this->filterwords($v);
                $this->$k = $v;
                $this->params[$k] = $v;
                $this->objects[] = $v;
            }
        }
    } // End of construct method:
    /*
     * Create a short description of content and place a link button that I call 'more' at the end of the
     * shorten content.
     */
    #[Pure] public function intro($content = "", $count = 100): string
    {
        return substr($content, 0, $count) . "...";
    }

    public function setImagePath($image_path)
    {
        $this->image_path = $image_path;

    }


    public function totalCount()
    {
        $sql = "SELECT count(id) FROM gallery";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function countAllPage($category = 'lego')
    {
        $sql = "SELECT count(id) FROM gallery WHERE category=:category";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(['category' => $category]);
        return $stmt->fetchColumn();

    }

    protected function filterwords($text)
    {
        $filterWords = array('fuck', 'shit', 'ass', 'asshole', 'motherfucker');
        $filterCount = sizeof($filterWords);
        for ($i = 0; $i < $filterCount; $i++) {
            $text = preg_replace_callback('/\b' . $filterWords[$i] . '\b/i', function ($matches) {
                return str_repeat('*', strlen($matches[0]));
            }, $text);
        }
        return $text;
    }



    public function page($perPage, $offset, $page = "index", $category = "home"): array
    {
        $sql = 'SELECT * FROM gallery WHERE page =:page AND category =:category ORDER BY id DESC, date_added DESC LIMIT :perPage OFFSET :blogOffset';
        $stmt = $this->pdo->prepare($sql); // Prepare the query:
        $stmt->execute(['page' => $page, 'perPage' => $perPage, 'category' => $category, 'blogOffset' => $offset]); // Execute the query with the supplied data:
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(): bool
    {
        /* Initialize an array */
        $attribute_pairs = [];

        /*
         * Set up the query using prepared states with static:$params being
         * the columns and the array keys being the prepared named placeholders.
         */
        $sql = 'INSERT INTO gallery (' . implode(", ", array_keys($this->params)) . ')';
        $sql .= ' VALUES ( :' . implode(', :', array_keys($this->params)) . ')';

        /*
         * Prepare the Database Table:
         */
        $stmt = $this->pdo->prepare($sql); // PHP Version 8.x Database::pdo()

        /*
         * Grab the corresponding values in order to
         * insert them into the table when the script
         * is executed.
         */
        foreach ($this->params as $key => $value) {
            if ($key === 'id') {
                continue; // Don't include the id:
            }
            $attribute_pairs[] = $value; // Assign it to an array:
        }

        return $stmt->execute($attribute_pairs); // Execute and send boolean true:
    }

} // End of class:

