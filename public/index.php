<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\MainController;
use App\Database;
use App\Repository\ArticleRepository\ArticleRepository;
use App\Repository\CategoryRepository\CategoryRepository;
use App\Router;
use App\Template;

$router = new Router();

$template = new Template();
$database = new Database();
$connection = $database->getConnection();

$categoryRepository = new CategoryRepository($connection);
$articlesRepository = new ArticleRepository($connection);

$router->get('/', new MainController(
    $categoryRepository,
    $articlesRepository,
    $template,
));

$router->get('/category/{category_id}', new CategoryController(
    $template,
    $categoryRepository,
    $articlesRepository,
));

$router->get('/article/{article_id}', new ArticleController(
    $template,
    $articlesRepository,
    $categoryRepository,
));

$router->dispatch();
