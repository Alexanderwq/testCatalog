<?php

namespace App\Repository\ArticleRepository;

use DateTimeImmutable;
use Exception;
use PDO;

readonly class ArticleRepository
{
    public function __construct(private PDO $dbClient)
    {
    }

    /**
     * @param int $categoryId
     * @param ?string $sort
     * @return ArticleDto[]
     * @throws Exception
     */
    public function getArticlesByCategory(int $categoryId, ?string $sort): array
    {
        $query = "
            SELECT a.id, a.name, a.description, a.content, a.created_at, a.views_count as viewsCount, ac.category_id, a.id
            FROM articles a
            JOIN article_categories ac ON a.id = ac.article_id and ac.category_id = :categoryId
        ";

        if ($sort == "date") {
            $query .= " ORDER BY a.created_at DESC";
        } else if ($sort == "views") {
            $query .= " ORDER BY a.views_count DESC";
        } else {
            $query .= " ORDER BY a.id DESC";
        }

        $stmt = $this->dbClient->prepare($query);

        $stmt->execute([':categoryId' => $categoryId]);

        return array_map(
            fn(array $row) => new ArticleDto(
                $row['id'],
                $row['name'],
                $row['description'],
                $row['content'],
                new DateTimeImmutable($row['created_at']),
                $row['viewsCount']
            ),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }
}