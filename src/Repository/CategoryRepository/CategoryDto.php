<?php

namespace App\Repository\CategoryRepository;

class CategoryDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
    ) {
    }
}