<?php
class Pagination {
    public static function Pagination($totalPages, $page, $path, $id = null) {
        $allowedPaths = ['shop', 'gallery/category'];
        if (!in_array($path, $allowedPaths, true)) {
            $path = 'shop';
        }

        if ($totalPages > 1) {
            echo '<div class="pagination">';

            $pages = [];
            $delta = 2;

            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i <= 3 || $i > $totalPages - 3 || ($i >= $page - $delta && $i <= $page + $delta)) {
                    $pages[] = $i;
                }
            }

            $pages = array_unique($pages);
            sort($pages);

            $lastPage = 0;

            foreach ($pages as $i) {
                if ($lastPage && $i - $lastPage > 1) {
                    if ($i - $lastPage == 2) {
                        $missingPage = $lastPage + 1;
                        $url = BASE_URL . '/' . APP_LANG . '/' . $path;
                        if ($path === 'shop' && $id !== null) {
                            $url .= '?category_id=' . $id . '&page=' . $missingPage;
                        } else {
                            $url .= '?id=' . $id . '&page=' . $missingPage;
                        }
                        echo '<a href="' . $url . '">' . $missingPage . '</a>';
                    } else {
                        echo '<span>...</span>';
                    }
                }

                if ($i === $page) {
                    echo '<span class="active">' . $i . '</span>';
                } else {
                    $url = BASE_URL . '/' . APP_LANG . '/' . $path;
                    if ($path === 'shop' && $id !== null) {
                        $url .= '?category_id=' . $id . '&page=' . $i;
                    } else {
                        $url .= '?id=' . $id . '&page=' . $i;
                    }
                    echo '<a href="' . $url . '">' . $i . '</a>'; 
                }
                $lastPage = $i;
            }
        }
    }
}