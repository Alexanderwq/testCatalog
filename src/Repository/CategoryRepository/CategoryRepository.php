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

    public function getRandomCategoryByArticle(int $articleId): int
    {
        $query = "
            SELECT article_categories.category_id from article_categories
            where article_id = :articleId
            order by rand()
            limit 1;
        ";

        $stmt = $this->dbClient->prepare($query);
        $stmt->execute([':articleId' => $articleId]);

        $result = $stmt->fetchColumn();

        if ($result === false) {
            throw new Exception("Not found category");
        }

        return $result;
    }

    /**
     * @return CategoryDto[]
     */
    public function getCategoriesWithArticles(): array
    {
        $query = "
            SELECT c.id, c.name, c.description FROM article_categories ac
            JOIN categories c ON ac.category_id = c.id
            GROUP BY ac.category_id
        ";

        $stmt = $this->dbClient->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return array_map(fn(array $category) => new CategoryDto(
            $category['id'],
            $category['name'],
            $category['description'],
        ), $result);
    }
}