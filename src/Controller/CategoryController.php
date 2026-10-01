<?php

namespace App\Controller;

use App\Repository\ArticleRepository\ArticleRepository;
use App\Repository\CategoryRepository\CategoryRepository;
use App\Request\CategoryRequest;
use App\Service\Pagination;
use App\Template;
use Exception;

readonly class CategoryController
{
    private Pagination $pagination;

    public function __construct(
        private Template           $template,
        private CategoryRepository $categoryRepository,
        private ArticleRepository  $articleRepository,
    )
    {
        $this->pagination = new Pagination();
    }

    /**
     * @throws Exception
     */
    public function __invoke(string $categoryId): string
    {
        $request = CategoryRequest::from(
            $categoryId,
            $_GET['sort'] ?? '',
            $_GET['page'] ?? 1,
        );

        $total = $this->articleRepository->countByCategory($categoryId);
        $paginationData = $this->pagination->handle($total, $request->page);

        try {
            $category = $this->categoryRepository->getCategoryById($categoryId);
        } catch (Exception $exception) {
            return $this->notFoundPage();
        }

        $articles = $this->articleRepository->getArticlesByCategory(
            $request->categoryId,
            $request->sort,
            $request->page,
        );

        return $this->template->render('pages/category.tpl', [
            'category' => $category,
            'articles' => $articles,
            'sort' => $request->sort,
            'from' => $paginationData['from'],
            'to' => $paginationData['to'],
            'page' => $request->page,
            'pages' => $paginationData['pages'],
        ]);
    }

    private function notFoundPage(): string
    {
        return $this->template->render('pages/404.tpl');
    }
}