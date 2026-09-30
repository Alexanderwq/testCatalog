<?php

namespace App\Repository\ArticleCategoriesRepository;

use App\Repository\CategoryRepository\CategoryDto;
use PDO;

readonly class ArticleCategoriesRepository
{

    public function __construct(
        private PDO $dbClient,
    ) {
    }

    /**
     * @param CategoryDto[] $categories
     * @return array
     */
    public function getLastArticlesByCategories(array $categories): array
    {
        $limit = 3;

        $parts = [];
        foreach ($categories as $category) {
            $id = $category->id;
            $parts[] = "(SELECT article_id, category_id
                     FROM article_categories
                     WHERE category_id = $id
                     ORDER BY article_created_at DESC, article_id DESC
                     LIMIT $limit)";
        }

        $sql = implode(' UNION ALL ', $parts) . ' ORDER BY category_id';

        $stmt = $this->dbClient->prepare($sql);
        $stmt->execute();

        $result = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $result[(int) $row['category_id']][] = (int) $row['article_id'];
        }

        return $result;
    }
}