<?php
declare(strict_types=1);

namespace App\Domain\Author;

interface AuthorRepository
{
    /**
     * @return Author[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Author
     * @throws AuthorNotFoundException
     */
    public function findAuthorOfId(int $id): Author;
}
