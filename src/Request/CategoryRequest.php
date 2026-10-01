<?php

namespace App\Request;

use Exception;

class CategoryRequest
{
    private const ALLOWED_SORT_FIELDS = ["views", "date"];

    public function __construct(
        public int $categoryId,
        public string $sort,
        public int $page,
    ) {
    }

    /**
     * @throws Exception
     */
    public static function from($categoryId, $sort, $page): self
    {
        self::validate($categoryId, $sort, $page);

        return new self($categoryId, $sort, $page);
    }

    /**
     * @throws Exception
     */
    public static function validate($categoryId, $sort, $page): void
    {
        $categoryId = filter_var($categoryId, FILTER_VALIDATE_INT);

        if ($categoryId === false || $categoryId < 1) {
            throw new Exception("Invalid category ID");
        }

        if ($page < 1) {
            throw new Exception("Invalid page");
        }

        if ($sort && !in_array($sort, self::ALLOWED_SORT_FIELDS, true)) {
            throw new Exception("Request exception");
        }
    }
}