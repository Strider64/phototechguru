<?php

namespace PhotoTech;

interface GalleryInterface
{
    public function intro($content = "", $count = 100): string;
    public function setImagePath($image_path);
    public function totalCount();
    public function countAllPage($category = 'lego');
    public function total_pages($total_count, $per_page): float|bool;
    public function offset($per_page, $current_page): float|int;
    public function page($perPage, $offset, $page = "index", $category = "home"): array;
    public function create(): bool;
}