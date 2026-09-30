<?php

namespace App\Controller;

use App\Template;
use PDO;

readonly class MainController
{
    public function __construct(private PDO $dbClient, private Template $template)
    {
    }

    public function __invoke(): string
    {
        $stat = $this->dbClient->query("
            SELECT DISTINCT id from categories
            JOIN article_categories ON article_categories.category_id = categories.id
        ");

        $result = $stat->fetchAll(PDO::FETCH_COLUMN);

        return $this->template->render('home.tpl', [
            'categories' => [
                [
                    "name" => "my category",
                    "slug" => "my_category",
                    "posts" => [
                        [
                            "title" => "my title",
                            "description" => "my description",
                            "slug" => "my_slug",
                            "author" => "my author",
                            "created_at" => "2025.04.04"
                        ]
                    ]
                ]
            ],
        ]);
    }
}