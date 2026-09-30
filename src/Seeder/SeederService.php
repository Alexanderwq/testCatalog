<?php

namespace App\Seeder;

use PDO;

readonly class SeederService
{
    public function __construct(
        private PDO $dbClient,
        private CategorySeeder $categorySeeder,
        private ArticlesSeeder $articlesSeeder,
        private ArticleCategoriesSeeder $articleCategoriesSeeder,
    ) {
    }

    public function run(): void
    {
        $this->dropTables();
        $this->createTables();

        $this->categorySeeder->run();
        $this->articlesSeeder->run();
        $this->articleCategoriesSeeder->run();
    }

    private function createTables(): void
    {
        $queries = [
            'categories' => "
                CREATE TABLE IF NOT EXISTS categories (
                    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name        VARCHAR(255) NOT NULL,
                    description TEXT NULL,
                    created_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    CONSTRAINT uq_categories_name UNIQUE (name)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ",

            'articles' => "
                CREATE TABLE IF NOT EXISTS articles (
                    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    image       VARCHAR(500) NULL,
                    name        VARCHAR(255) NOT NULL,
                    description TEXT NULL,
                    content     LONGTEXT NOT NULL,
                    views_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
                    created_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX idx_articles_created_at (created_at),
                    INDEX idx_articles_name (name),
                    INDEX idx_articles_views (views_count)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ",

            'article_categories' => "
                CREATE TABLE IF NOT EXISTS article_categories (
                    article_id  BIGINT UNSIGNED NOT NULL,
                    category_id BIGINT UNSIGNED NOT NULL,
                    PRIMARY KEY (article_id, category_id),
                    CONSTRAINT fk_article_categories_article
                        FOREIGN KEY (article_id) REFERENCES articles (id)
                        ON UPDATE CASCADE ON DELETE CASCADE,
                    CONSTRAINT fk_article_categories_category
                        FOREIGN KEY (category_id) REFERENCES categories (id)
                        ON UPDATE CASCADE ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ",
        ];

        foreach ($queries as $sql) {
            $this->dbClient->exec($sql);
        }
    }

    private function dropTables(): void
    {
        foreach (['article_categories', 'articles', 'categories'] as $table) {
            $this->dbClient->exec("DROP TABLE IF EXISTS `$table`");
        }
    }
}