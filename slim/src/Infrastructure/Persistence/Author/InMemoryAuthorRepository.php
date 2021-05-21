<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Author;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorNotFoundException;
use App\Domain\Author\AuthorRepository;

class InMemoryAuthorRepository implements AuthorRepository
{
    /**
     * @var Author[]
     */
    private $authors;

    /**
     * InMemoryAuthorRepository constructor.
     *
     * @param array|null $authors
     */
    public function __construct(array $authors = null)
    {
        if (is_readable(AUTHOR_DB)) {
            $json = file_get_contents(AUTHOR_DB);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $data = json_decode($json, true);
            for ($i = 1; $i <= count($data); $i++) {
                if (isset($data[$i - 1])) {
                    $data[$i - 1]['id'] = (int) $data[$i - 1]['id'];
                    $authors[$i] = new Author($i, $data[$i - 1]);
                }
            }
        }

        $this->authors = $authors ?? [
            1 => new Author(1, []),
            2 => new Author(2, []),
            3 => new Author(3, []),
            4 => new Author(4, []),
            5 => new Author(5, []),
        ];
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
