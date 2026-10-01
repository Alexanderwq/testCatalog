<?php

namespace App\Repository\ArticleRepository;

use App\Repository\CategoryRepository\CategoryDto;
use DateTimeImmutable;
use Exception;
use PDO;

readonly class ArticleRepository
{
    public const PER_PAGE = 20;

    public function __construct(private PDO $dbClient)
    {
    }

    public function countByCategory(int $categoryId): int
    {
        $stmt = $this->dbClient->prepare(
            'SELECT COUNT(*) FROM article_categories WHERE category_id = :categoryId'
        );
        $stmt->execute([':categoryId' => $categoryId]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * @param int $categoryId
     * @param ?string $sort
     * @param int $page
     * @return ArticleDto[]
     * @throws Exception
     */
    public function getArticlesByCategory(int $categoryId, ?string $sort, int $page): array
    {
        $query = "
            SELECT a.id, a.name, a.description, a.content, a.created_at, a.image, a.views_count as viewsCount, ac.category_id, a.id
            FROM articles a
            JOIN article_categories ac ON a.id = ac.article_id and ac.category_id = :categoryId
        ";

        if ($sort == "date") {
            $query .= " ORDER BY a.created_at DESC, a.id DESC";
        } else if ($sort == "views") {
            $query .= " ORDER BY a.views_count DESC, a.id DESC";
        } else {
            $query .= " ORDER BY a.id DESC";
        }

        $query .= ' LIMIT :perPage OFFSET :offset';

        $stmt = $this->dbClient->prepare($query);

        $stmt->execute([
            ':categoryId' => $categoryId,
            ':perPage' => self::PER_PAGE,
            ':offset' => ($page - 1) * self::PER_PAGE,
        ]);

        return array_map(
            fn(array $row) => new ArticleDto(
                id: $row['id'],
                name: $row['name'],
                content: $row['content'],
                description: $row['description'],
                image: $row['image'],
                createdAt: new DateTimeImmutable($row['created_at']),
                viewsCount: $row['viewsCount']
            ),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    /**
     * @throws Exception
     */
    public function getArticleById(int $articleId): ArticleDto
    {
        $query = "SELECT id, name, description, views_count, content, image, created_at FROM articles WHERE id = :articleId";

        $stmt = $this->dbClient->prepare($query);
        $stmt->execute([':articleId' => $articleId]);

        $articleRaw = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($articleRaw === false) {
            throw new Exception("Not found article");
        }

        return new ArticleDto(
            id: $articleRaw['id'],
            name: $articleRaw['name'],
            content: $articleRaw['content'],
            description: $articleRaw['description'],
            image: $articleRaw['image'],
            createdAt: new DateTimeImmutable($articleRaw['created_at']),
            viewsCount: $articleRaw['views_count'],
        );
    }

    /**
     * @param int $categoryId
     * @param int $articleId
     * @return int[]
     */
    public function getRecommendedArticlesByCategory(int $categoryId, int $articleId): array
    {
        $query = "
            SELECT article_id FROM article_categories 
            WHERE category_id = :categoryId and article_id != :articleId
            ORDER BY RAND()
            LIMIT 3
        ";

        $stmt = $this->dbClient->prepare($query);
        $stmt->execute([':categoryId' => $categoryId, ':articleId' => $articleId]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * @param int[] $ids
     * @return ArticleDto[]
     * @throws Exception
     */
    public function getArticles(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $ids = array_values(array_unique(array_map('intval', $ids)));
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $query = "
            SELECT id, name, description, views_count, content, image, created_at
            FROM articles
            WHERE id IN ($placeholders)
            ORDER BY created_at DESC
        ";

        $stmt = $this->dbClient->prepare($query);
        $stmt->execute($ids);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn(array $row) => new ArticleDto(
                id: (int) $row['id'],
                name: $row['name'],
                content: $row['content'],
                description: $row['description'],
                image: $row['image'],
                createdAt: new DateTimeImmutable($row['created_at']),
                viewsCount: (int) $row['views_count']
            ),
            $rows
        );
    }

    /**
     * @param CategoryDto[] $categories
     * @param int $limit
     * @return array
     * @throws Exception
     */
    public function getLastArticlesByCategories(array $categories, int $limit = 3): array
    {
        $parts = [];
        foreach ($categories as $category) {
            $id = $category->id;
            $parts[] = "(
                SELECT a.id, a.name, a.views_count, a.image, a.content, a.description, a.created_at, ac.category_id
                FROM articles a FORCE INDEX (idx_articles_created_at)
                JOIN article_categories ac ON ac.article_id = a.id AND ac.category_id = $id
                ORDER BY a.created_at DESC
                LIMIT $limit)";
        }

        $sql = implode(' UNION ALL ', $parts) . ' ORDER BY category_id, created_at DESC';

        $stmt = $this->dbClient->prepare($sql);
        $stmt->execute();

        $result = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $result[(int) $row['category_id']][] = new ArticleDto(
                id: $row['id'],
                name: $row['name'],
                content: $row['content'],
                description: $row['description'],
                image: $row['image'],
                createdAt: new DateTimeImmutable($row['created_at']),
                viewsCount: $row['views_count'],
            );
        }

        return $result;
    }
}