<?php

use App\Database;
use App\Seeder\ArticleCategoriesSeeder;
use App\Seeder\ArticlesSeeder;
use App\Seeder\CategorySeeder;
use App\Seeder\SeederService;

require_once __DIR__ . '/../vendor/autoload.php';

$database = new Database();
$connection = $database->getConnection();

$seederService = new SeederService(
    $connection,
    new CategorySeeder($connection),
    new ArticlesSeeder($connection),
    new ArticleCategoriesSeeder($connection),
);

$seederService->run();