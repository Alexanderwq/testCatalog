<?php

namespace App\Controller;

use App\Repository\ArticleRepository\ArticleRepository;
use App\Repository\CategoryRepository\CategoryRepository;
use App\Template;

readonly class ArticleController
{
    public function __construct(
        private Template $template,
        private ArticleRepository $articleRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function __invoke(int $articleId): string
    {
        $article = $this->articleRepository->getArticleById($articleId);

        $categoryId = $this->categoryRepository->getRandomCategory($articleId);
        $recommendedArticleIds = $this->articleRepository->getRecommendedArticlesByCategory($categoryId, $articleId);

        $recommendedArticles = $this->articleRepository->getArticles($recommendedArticleIds);

        return $this->template->render('pages/article.tpl', [
            'article' => $article,
            'recommendedArticles' => $recommendedArticles,
        ]);
    }
}