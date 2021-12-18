<?php

namespace PhotoTech;

use JetBrains\PhpStorm\Pure;

class Pagination_New
{
    public int $current_page;
    public int $per_page;
    public int $total_count;
    static public string $links = "";


    public function __construct($page = 1, $per_page = 20, $total_count = 0)
    {
        $this->current_page = (int)$page;
        $this->per_page = (int)$per_page;
        $this->total_count = (int)$total_count;
    }

    public function offset(): float|int
    {
        return $this->per_page * ($this->current_page - 1);
    }

    #[Pure] public function total_pages(): float|bool
    {
        return ceil($this->total_count / $this->per_page);
    }

    public function previous_page(): bool|int
    {
        $prev = $this->current_page - 1;
        return ($prev > 0) ? $prev : false;
    }

    #[Pure] public function next_page(): bool|int
    {
        $next = $this->current_page + 1;
        return ($next <= $this->total_pages()) ? $next : false;
    }

    public function total_links_per_page() {
        return ceil($this->total_pages() / 5);
    }

    public function previous_link($url=""): string
    {
        if($this->previous_page() !== false) {
            static::$links .= '<a class="flex-item word-link" href="' . $url . '?page=' . $this->previous_page() . '">';
            static::$links .= "&laquo;</a>";
        }
        return static::$links;
    }

    public function next_link($url = ""): string
    {

        if ($this->next_page() !== false) {
            static::$links .= '<a class="flex-item word-link" href="' . $url . '?page=' . $this->next_page() . '">';
            static::$links .= "&raquo;</a>";
        }

        return static::$links;
    }

    public function number_links(): string
    {

        if ($this->total_pages() >= 1 && $this->current_page <= $this->total_pages()) {

            /* First Page Check */
            if ($this->current_page === 1) {
                static::$links .= '<a class="flex-item selected" href="?page=1">1</a>';
            } else {
                static::$links .= '<a class="flex-item" href="?page=1">1</a>';
            }

            /* Dashes */
            $i = max(2, $this->current_page - 3);
            if ($i > 2)
                static::$links .= '<a class="flex-item dashes" href="#">...</a>';

            /* Multiple Links For Loop */
            for (; $i < min($this->current_page + 3, $this->total_pages()); $i++) {
                if ($this->current_page === $i ) {
                    static::$links .= '<a class="flex-item selected" href="?page=' . $i . '">' . $i . '</a>';
                } else {
                    static::$links .= '<a class="flex-item" href="?page=' . $i . '">' . $i . '</a>';
                }
            }

            /* Dashes */
            if ($i != $this->total_pages())
                static::$links .= '<a class="flex-item dashes" href="#">...</a>';

            /* Last Page */
            if ($this->current_page == $this->total_pages()) {
                static::$links .= '<a class="flex-item selected" href="?page=' .$this->total_pages() . '">' . $this->total_pages() . '</a>';
            } else {
                static::$links .= '<a class="flex-item" href="?page=' .$this->total_pages() . '">' . $this->total_pages() . '</a>';
            }

        }


        return static::$links;
    }

    public function links()
    {
        $this->previous_link();
        $this->number_links();
        $this->next_link();
        return static::$links;
    }

}