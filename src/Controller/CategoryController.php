<?php

namespace App\Controller;

use App\Repository\ArticleRepository\ArticleRepository;
use App\Repository\CategoryRepository\CategoryRepository;
use App\Template;
use Exception;

readonly class CategoryController
{
    public function __construct(
        private Template $template,
        private CategoryRepository $categoryRepository,
        private ArticleRepository $articleRepository,
    ) {
    }

    public function __invoke(string $categoryId): string
    {
        $categoryId = filter_var($categoryId, FILTER_VALIDATE_INT);
        $sort = $_GET['sort'] ?? null;

        if ($categoryId === false || $categoryId < 1) {
            return $this->template->render('pages/404.tpl');
        }

        try {
            $category = $this->categoryRepository->getCategoryById($categoryId);
        } catch (Exception $exception) {
            return $this->template->render('pages/404.tpl');
        }

        $articles = $this->articleRepository->getArticlesByCategory($categoryId, $sort);

        return $this->template->render('pages/category.tpl', [
            'category' => $category,
            'articles' => $articles,
            'sort' => $sort,
        ]);
    }
}