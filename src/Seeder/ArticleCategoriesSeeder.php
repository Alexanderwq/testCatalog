<?php

namespace App\Seeder;

use PDO;
use Random\RandomException;
use Throwable;

class ArticleCategoriesSeeder
{
    private const MIN_CATEGORIES_PER_ARTICLE = 1;

    private const MAX_CATEGORIES_PER_ARTICLE = 3;

    private const BATCH_SIZE = 2500;

    public function __construct(private readonly PDO $dbClient)
    {
    }

    /**
     * @throws Throwable
     * @throws RandomException
     */
    public function run(): void
    {
        $articleIds = $this->dbClient->query('SELECT id FROM articles')->fetchAll(PDO::FETCH_COLUMN);
        $categoryIds = $this->dbClient->query('SELECT id FROM categories')->fetchAll(PDO::FETCH_COLUMN);

        $max = min(self::MAX_CATEGORIES_PER_ARTICLE, count($categoryIds));

        $batch = [];
        $rows = 0;

        try {
            foreach ($articleIds as $articleId) {
                $countRandomCategories = random_int(self::MIN_CATEGORIES_PER_ARTICLE, $max);
                $randomCategories = (array)array_rand($categoryIds, $countRandomCategories);

                foreach ($randomCategories as $key) {
                    $batch[] = (int)$articleId;
                    $batch[] = (int)$categoryIds[$key];
                    $rows++;

                    if ($rows >= self::BATCH_SIZE) {
                        $this->batchInsert($batch, $rows);
                        $batch = [];
                        $rows = 0;
                    }
                }
            }

            if ($rows > 0) {
                $this->batchInsert($batch, $rows);
            }

        } catch (Throwable $exception) {
            echo $exception->getMessage();
            throw $exception;
        }
    }

    private function batchInsert(array $values, int $rows): void
    {
        $sql = 'INSERT INTO article_categories (article_id, category_id) VALUES '
            . implode(',', array_fill(0, $rows, '(?,?)'));

        $this->dbClient->prepare($sql)->execute($values);
    }
}