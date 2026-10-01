<?php

namespace App\Service;

use App\Repository\ArticleRepository\ArticleRepository;
use Exception;

class Pagination
{
    private const WINDOW = 6;

    /**
     * @throws Exception
     */
    public function handle(int $total, int $page): array
    {
        $pages = (int) ceil($total / ArticleRepository::PER_PAGE);
        $from = max(1, min($page - 2, $pages - self::WINDOW + 1));
        $to = min($pages, $from + self::WINDOW - 1);

        $this->validateCurrentPage($page, $pages);

        return [
            'from' => $from,
            'to' => $to,
            'pages' => $pages,
        ];
    }

    /**
     * @throws Exception
     */
    private function validateCurrentPage(int $page, int $pages): void
    {
        if ($page > $pages) {
            throw new Exception("Invalid current page");
        }
    }
}