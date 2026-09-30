<?php

namespace App\Controller;

use App\Repository\ArticleCategoriesRepository\ArticleCategoriesRepository;
use App\Repository\ArticleRepository\ArticleDto;
use App\Repository\ArticleRepository\ArticleRepository;
use App\Repository\CategoryRepository\CategoryRepository;
use App\Template;

readonly class MainController
{
    public function __construct(
        private ArticleCategoriesRepository $articleCategoriesRepository,
        private CategoryRepository $categoryRepository,
        private ArticleRepository $articleRepository,
        private Template $template,
    ) {
    }

    public function __invoke(): string
    {
        $categories = $this->categoryRepository->getCategoriesWithArticles();
        $articlesMap = $this->articleCategoriesRepository->getLastArticlesByCategories($categories);
        $articlesIds = array_merge(...array_values($articlesMap));
        $articles = $this->articleRepository->getArticles($articlesIds);

        $articlesByCategory = [];

        foreach ($categories as $category) {
            $categoryArticleIds = $articlesMap[$category->id];
            $articlesByCategory[$category->id] = array_filter(
                $articles,
                fn(ArticleDto $article) => in_array(
                    $article->id,
                    $categoryArticleIds,
                    true
                )
            );
        }

        return $this->template->render('pages/home.tpl', [
            'articlesByCategory' => $articlesByCategory,
            'categories' => $categories,
        ]);
    }
}