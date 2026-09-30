<?php

namespace App\Service;

class Pagination
{
    private const WINDOW = 6;

    public function handle(int $pages, int $page): array
    {
        $from = max(1, min($page - 2, $pages - self::WINDOW + 1));
        $to = min($pages, $from + self::WINDOW - 1);

        return [
            'from' => $from,
            'to' => $to,
            'page' => $page,
            'pages' => $pages,
        ];
    }
}