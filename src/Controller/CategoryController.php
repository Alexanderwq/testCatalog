<?php

namespace App\Controller;

use App\Repository\ArticleRepository\ArticleRepository;
use App\Repository\CategoryRepository\CategoryRepository;
use App\Service\Pagination;
use App\Template;
use Exception;

readonly class CategoryController
{
    private Pagination $pagination;

    public function __construct(
        private Template $template,
        private CategoryRepository $categoryRepository,
        private ArticleRepository  $articleRepository,
    ) {
        $this->pagination = new Pagination();
    }

    public function __invoke(string $categoryId): string
    {
        $categoryId = filter_var($categoryId, FILTER_VALIDATE_INT);
        $sort = $_GET['sort'] ?? null;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $total = $this->articleRepository->countByCategory($categoryId);
        $pages = (int) ceil($total / ArticleRepository::PER_PAGE);
        $paginationData = $this->pagination->handle($pages, $page);

        if ($categoryId === false || $categoryId < 1 || $page > $pages || $page < 1) {
            return $this->template->render('pages/404.tpl');
        }

        try {
            $category = $this->categoryRepository->getCategoryById($categoryId);
        } catch (Exception $exception) {
            return $this->template->render('pages/404.tpl');
        }

        $articles = $this->articleRepository->getArticlesByCategory($categoryId, $sort, $page);

        return $this->template->render('pages/category.tpl', [
            'category' => $category,
            'articles' => $articles,
            'sort' => $sort,
            ...$paginationData,
        ]);
    }
}