<?php

namespace App\Repository\ArticleRepository;

use DateTimeImmutable;

class ArticleDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $content,
        public string $description,
        public string $image,
        public DateTimeImmutable $createdAt,
        public int $viewsCount,
    ) {
    }
}