<?php

namespace App\Controller;

use App\Repository\ArticleRepository\ArticleRepository;
use App\Repository\CategoryRepository\CategoryRepository;
use App\Template;
use Exception;

readonly class MainController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private ArticleRepository $articleRepository,
        private Template $template,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(): string
    {
        $categories = $this->categoryRepository->getCategoriesWithArticles();
        $articlesMap = $this->articleRepository->getLastArticlesByCategories($categories);

        return $this->template->render('pages/home.tpl', [
            'articlesByCategory' => $articlesMap,
            'categories' => $categories,
        ]);
    }
}