<?php
declare(strict_types=1);

namespace App\Application\Actions\Author;

use Psr\Http\Message\ResponseInterface as Response;

class ListAuthorsAction extends AuthorAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $authors = $this->authorRepository->findAll();

        $this->logger->info('Authors list was viewed.');

        return $this->respondWithData($authors);
    }
}
