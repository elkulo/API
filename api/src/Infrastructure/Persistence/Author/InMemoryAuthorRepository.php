<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Author;

use App\Application\Settings\SettingsInterface;
use App\Domain\Author\Author;
use App\Domain\Author\AuthorNotFoundException;
use App\Domain\Author\AuthorRepository;

class InMemoryAuthorRepository implements AuthorRepository
{
    /**
     * @var Author[]
     */
    private array $authors;

    public function __construct(SettingsInterface $settings)
    {
        $authors = [];

        $src = $settings->get('author.src');

        if (is_readable($src)) {
            $json = file_get_contents($src);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $data = json_decode($json, true);
            $max = count($data);
            for ($i = 1; $i <= $max; $i++) {
                if (isset($data[$i - 1])) {
                    $authors[$i] = new Author($i, $data[$i - 1]);
                }
            }
        }

        $this->authors = $authors;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->authors);
    }

    /**
     * {@inheritdoc}
     */
    public function findAuthorOfId(int $id): Author
    {
        if (!isset($this->authors[$id])) {
            throw new AuthorNotFoundException();
        }

        return $this->authors[$id];
    }
}
