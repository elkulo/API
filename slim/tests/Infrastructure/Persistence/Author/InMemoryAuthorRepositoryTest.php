<?php
declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Author;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorNotFoundException;
use App\Infrastructure\Persistence\Author\InMemoryAuthorRepository;
use Tests\TestCase;

class InMemoryAuthorRepositoryTest extends TestCase
{
    
    public function testFindAll(InMemoryAuthorRepository $authorRepository)
    {
        $author = new Author(1, []);

        $authorRepository = $authorRepository;

        $this->assertEquals([$author], $authorRepository->findAll());
    }

    public function testFindAllAuthorsByDefault(InMemoryAuthorRepository $authorRepository)
    {
        $authors = [
            1 => new Author(1, []),
            2 => new Author(2, []),
            3 => new Author(3, []),
            4 => new Author(4, []),
            5 => new Author(5, []),
        ];

        $this->assertEquals(array_values($authors), $authorRepository->findAll());
    }

    public function testFindAuthorOfId(InMemoryAuthorRepository $authorRepository)
    {
        $author = new Author(1, []);

        $this->assertEquals($author, $authorRepository->findAuthorOfId(1));
    }

    public function testFindAuthorOfIdThrowsNotFoundException(InMemoryAuthorRepository $authorRepository)
    {
        $authorRepository = $authorRepository;
        $this->expectException(AuthorNotFoundException::class);
        $authorRepository->findAuthorOfId(1);
    }
}
