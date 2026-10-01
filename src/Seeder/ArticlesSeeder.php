<?php

namespace App\Seeder;

use DateTimeImmutable;
use PDO;
use Random\RandomException;
use Throwable;

class ArticlesSeeder
{
    private const ARTICLES = [
        [
            'image' => 'pasta-carbonara.jpg',
            'name' => 'Классическая паста карбонара',
            'description' => 'Простой рецепт настоящей итальянской карбонары.',
            'content' => 'Паста карбонара — одно из самых известных блюд итальянской кухни. Для приготовления понадобятся спагетти, гуанчале или бекон, яйца, сыр пармезан или пекорино и свежемолотый чёрный перец. Главный секрет блюда — правильно соединить горячую пасту с яично-сырной смесью, не превратив её в омлет.',
        ],
        [
            'image' => 'cheesecake.jpg',
            'name' => 'Нежный чизкейк без выпечки',
            'description' => 'Простой десерт, который не требует духовки.',
            'content' => 'Этот чизкейк состоит из основы из песочного печенья и нежной сливочной начинки. Десерт готовится в холодильнике и отлично подходит для летнего чаепития.',
        ],
        [
            'image' => 'pancakes.jpg',
            'name' => 'Пышные американские панкейки',
            'description' => 'Идеальный вариант для неспешного воскресного завтрака.',
            'content' => 'Панкейки получаются особенно пышными благодаря разрыхлителю и правильной консистенции теста. Подавать их можно с ягодами, бананом, мёдом, кленовым сиропом или йогуртом.',
        ],
        [
            'image' => 'pizza.jpg',
            'name' => 'Домашняя пицца Маргарита',
            'description' => 'Минимум ингредиентов и максимум итальянского вкуса.',
            'content' => 'Для классической Маргариты нужны тесто, томатный соус, моцарелла, базилик и немного оливкового масла. Высокая температура духовки позволяет получить хрустящую корочку и мягкую середину.',
        ],
        [
            'image' => 'ramen.jpg',
            'name' => 'Домашний рамен с курицей',
            'description' => 'Ароматный японский суп, который можно приготовить дома.',
            'content' => 'Основой рамена является насыщенный бульон. В него добавляют лапшу, курицу, зелёный лук, яйцо и другие ингредиенты по вкусу. Рецепт легко адаптировать под продукты, которые есть дома.',
        ],
        [
            'image' => 'curry.jpg',
            'name' => 'Пряное индийское карри',
            'description' => 'Ароматное блюдо с курицей, кокосовым молоком и специями.',
            'content' => 'Карри позволяет экспериментировать со специями и овощами. Для основы можно использовать лук, чеснок, имбирь, карри, курицу и кокосовое молоко. Подавать блюдо лучше всего с рисом.',
        ],
        [
            'image' => 'tiramisu.jpg',
            'name' => 'Тирамису по-домашнему',
            'description' => 'Классический итальянский десерт с кофе и маскарпоне.',
            'content' => 'Тирамису состоит из пропитанных кофе савоярди, крема на основе маскарпоне и какао. Перед подачей десерт желательно оставить в холодильнике на несколько часов.',
        ],
        [
            'image' => 'bread.jpg',
            'name' => 'Как испечь хлеб дома',
            'description' => 'Простой рецепт домашнего хлеба с хрустящей корочкой.',
            'content' => 'Для домашнего хлеба понадобятся мука, вода, дрожжи, соль и немного времени. Долгая расстойка позволяет получить ароматный мякиш и красивую хрустящую корочку.',
        ],
        [
            'image' => 'paris.jpg',
            'name' => 'Что посмотреть в Париже за три дня',
            'description' => 'Маршрут для первого путешествия в Париж.',
            'content' => 'За три дня можно увидеть основные достопримечательности Парижа: Эйфелеву башню, Лувр, Монмартр, Нотр-Дам и Елисейские поля. При этом стоит оставить время для прогулок по небольшим улицам и кафе.',
        ],
        [
            'image' => 'rome.jpg',
            'name' => 'Рим: город, в который хочется вернуться',
            'description' => 'Прогулка по главным достопримечательностям Рима.',
            'content' => 'Рим сочетает античную историю, архитектуру, итальянскую кухню и современную городскую жизнь. Колизей, Пантеон, Римский форум и Ватикан можно объединить в маршрут на несколько дней.',
        ],
        [
            'image' => 'books.jpg',
            'name' => '10 книг, которые стоит прочитать',
            'description' => 'Подборка книг разных жанров для длинных вечеров.',
            'content' => 'В подборке собраны классические произведения, современная проза, научно-популярные книги и несколько детективов. Каждый читатель сможет найти что-то интересное для себя.',
        ],
        [
            'image' => 'coffee.jpg',
            'name' => 'Как приготовить хороший кофе дома',
            'description' => 'Разбираемся в помоле, температуре воды и способах заваривания.',
            'content' => 'Вкус кофе зависит от сорта зерна, степени обжарки, помола, температуры воды и времени экстракции. Даже небольшие изменения этих параметров могут заметно изменить результат.',
        ],
    ];

    private const ARTICLES_COUNT = 250000;

    private const BATCH_SIZE = 2000;

    public function __construct(private readonly PDO $dbClient)
    {
    }

    /**
     * @throws Throwable
     * @throws RandomException
     */
    public function run(): void
    {
        $columns = ['image', 'name', 'description', 'content', 'views_count', 'created_at'];
        $rowPlaceholder = '(' . implode(',', array_fill(0, count($columns), '?')) . ')';

        $countRows = 0;

        try {
            while ($countRows !== self::ARTICLES_COUNT) {
                $size = min(self::BATCH_SIZE, self::ARTICLES_COUNT - $countRows);

                $values = [];

                for ($i = 0; $i < $size; $i++) {
                    $article = $this->generateArticleData();

                    $values[] = $article['image'];
                    $values[] = $article['name'];
                    $values[] = $article['description'];
                    $values[] = $article['content'];
                    $values[] = $article['views_count'];
                    $values[] = $article['created_at'];
                }

                $sql = 'INSERT INTO articles (' . implode(',', $columns) . ') VALUES '
                    . implode(',', array_fill(0, $size, $rowPlaceholder));

                $this->dbClient->prepare($sql)->execute($values);

                $countRows += $size;
            }
        } catch (Throwable $exception) {
            echo $exception->getMessage();
            throw $exception;
        }
    }

    /**
     * @throws RandomException
     */
    private function generateArticleData(): array
    {
        $end = new DateTimeImmutable();
        $start = $end->modify('-3 months');

        $randomCreatedAt = random_int($start->getTimestamp(), $end->getTimestamp());
        $randomArticle = self::ARTICLES[array_rand(self::ARTICLES)];

        return [
            ...$randomArticle,
            'created_at' => (new DateTimeImmutable())->setTimestamp($randomCreatedAt)->format('Y-m-d H:i:s'),
            "views_count" => random_int(1, 5000),
        ];
    }
}