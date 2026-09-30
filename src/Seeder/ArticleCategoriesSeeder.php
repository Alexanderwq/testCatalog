<?php

namespace App\Seeder;

use PDO;
use Throwable;

class ArticleCategoriesSeeder
{
    private const MIN_CATEGORIES_PER_ARTICLE = 1;
    private const MAX_CATEGORIES_PER_ARTICLE = 3;

    public function __construct(private readonly PDO $dbClient)
    {
    }

    public function run(): void
    {
        $articleIds  = $this->dbClient->query('SELECT id FROM articles')->fetchAll(PDO::FETCH_COLUMN);
        $categoryIds = $this->dbClient->query('SELECT id FROM categories')->fetchAll(PDO::FETCH_COLUMN);

        if (!$articleIds || !$categoryIds) {
            return;
        }

        $link = $this->dbClient->prepare(
            'INSERT INTO article_categories (article_id, category_id) VALUES (:article_id, :category_id)'
        );

        $max = min(self::MAX_CATEGORIES_PER_ARTICLE, count($categoryIds));

        $this->dbClient->beginTransaction();

        try {
            foreach ($articleIds as $articleId) {
                $count = random_int(self::MIN_CATEGORIES_PER_ARTICLE, $max);

                foreach ((array) array_rand($categoryIds, $count) as $key) {
                    $link->execute([
                        ':article_id'  => (int) $articleId,
                        ':category_id' => (int) $categoryIds[$key],
                    ]);
                }
            }

            $this->dbClient->commit();
        } catch (Throwable $exception) {
            $this->dbClient->rollBack();
            throw $exception;
        }
    }
}