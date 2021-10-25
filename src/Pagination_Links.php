<?php

namespace PhotoTech;


class Pagination_Links extends DatabaseObject
{

    protected static string $table = "cms"; // Table Name:

    public static function countAllPage($page = 'blog') {
        static::$searchItem = 'page';
        static::$searchValue = $page;
        $sql = "SELECT count(id) FROM " . static::$table . " WHERE page=:page";
        $stmt = Database::pdo()->prepare($sql);

        $stmt->execute([ static::$searchItem => static::$searchValue ]);
        return $stmt->fetchColumn();
    }

    public static function total_pages($per_page): float|bool
    {
        return ceil(static::countAllPage()/ $per_page);
    }

}