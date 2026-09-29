<?php

namespace App\Repository\CategoryRepository;

use Exception;
use PDO;

readonly class CategoryRepository
{
    public function __construct(private PDO $dbClient)
    {
    }

    /**
     * @throws Exception
     */
    public function getCategoryById(int $categoryId): CategoryDto
    {
        $stmt = $this->dbClient->prepare("SELECT id, name, description FROM categories WHERE id = :id");
        $stmt->execute([':id' => $categoryId]);

        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category === false) {
            throw new Exception("Not found category");
        }

        return new CategoryDto($category['id'], $category['name'], $category['description']);
    }
}