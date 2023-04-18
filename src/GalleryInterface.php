<?php

namespace PhotoTech;

interface GalleryInterface
{
    public function intro($content = "", $count = 100): string;
    public function setImagePath($image_path);
    public function totalCount();
    public function countAllPage($category = 'lego');
    public function page($perPage, $offset, $page = "index", $category = "home"): array;
    public function create(): bool;
}