<?php

namespace App\Seeder;

use DateTimeImmutable;
use PDO;
use Random\RandomException;
use Throwable;

class CategorySeeder
{
    private const NAMES = [
        'Рецепты',
        'Завтраки',
        'Выпечка',
        'Итальянская кухня',
        'Азиатская кухня',
        'Путешествия',
        'Книги',
        'Кино',
        'Технологии',
        'Образ жизни',
    ];

    private const DESCRIPTIONS = [
        'Пошаговые рецепты вкусных блюд на каждый день.',
        'Идеи простых и сытных завтраков.',
        'Рецепты хлеба, пирогов, булочек и другой выпечки.',
        'Классические и современные блюда итальянской кухни.',
        'Рецепты блюд из разных стран Азии.',
        'Истории путешествий, полезные советы и интересные места.',
        'Обзоры книг, рекомендации и интересные факты о литературе.',
        'Обзоры фильмов, сериалы и рекомендации для просмотра.',
        'Новости, обзоры и полезные материалы о технологиях.',
        'Полезные идеи для повседневной жизни и личного развития.',
    ];

    public function __construct(private readonly PDO $pdo)
    {
    }

    public function run(): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO categories (name, description, created_at)
            VALUES (:name, :description, :created_at)
        ");

        $this->pdo->beginTransaction();

        try {
            foreach (array_keys(self::NAMES) as $index) {
                $category = $this->generateCategoryData($index);

                $stmt->execute([
                    ':name' => $category['name'],
                    ':description' => $category['description'],
                    ':created_at' => $category['created_at']->format('Y-m-d H:i:s'),
                ]);
            }

            $this->pdo->commit();
        } catch (Throwable $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }
    }

    /**
     * @throws RandomException
     */
    private function generateCategoryData(int $index): array
    {
        $end = new DateTimeImmutable();
        $start = $end->modify('-3 months');

        $randomCreatedAt = random_int($start->getTimestamp(), $end->getTimestamp());

        return [
            'name' => self::NAMES[$index],
            'description' => self::DESCRIPTIONS[$index],
            'created_at' => (new DateTimeImmutable())->setTimestamp($randomCreatedAt),
        ];
    }
}