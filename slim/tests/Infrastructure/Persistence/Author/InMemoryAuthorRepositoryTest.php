<?php
declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Author;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorNotFoundException;
use App\Infrastructure\Persistence\Author\InMemoryAuthorRepository;
use Tests\TestCase;

class InMemoryAuthorRepositoryTest extends TestCase
{
    public function testFindAll()
    {
        $author = new Author(1, []);

        $authorRepository = new InMemoryAuthorRepository([1 => $author]);

        $this->assertEquals([$author], $authorRepository->findAll());
    }

    public function testFindAllAuthorsByDefault()
    {
        $authors = [
            1 => new Author(1, []),
            2 => new Author(2, []),
            3 => new Author(3, []),
            4 => new Author(4, []),
            5 => new Author(5, []),
        ];

        $authorRepository = new InMemoryAuthorRepository();

        $this->assertEquals(array_values($authors), $authorRepository->findAll());
    }

    public function testFindAuthorOfId()
    {
        $author = new Author(1, []);

        $authorRepository = new InMemoryAuthorRepository([1 => $author]);

        $this->assertEquals($author, $authorRepository->findAuthorOfId(1));
    }

    public function testFindAuthorOfIdThrowsNotFoundException()
    {
        $authorRepository = new InMemoryAuthorRepository([]);
        $this->expectException(AuthorNotFoundException::class);
        $authorRepository->findAuthorOfId(1);
    }
}
