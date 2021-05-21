<?php
declare(strict_types=1);

namespace App\Application\Actions\Author;

use Psr\Http\Message\ResponseInterface as Response;

class ViewAuthorAction extends AuthorAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $authorId = (int) $this->resolveArg('id');
        $author = $this->authorRepository->findAuthorOfId($authorId);

        $this->logger->info("Author of id `${authorId}` was viewed.");

        return $this->respondWithData($author);
    }
}
