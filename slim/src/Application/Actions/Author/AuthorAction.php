<?php
declare(strict_types=1);

namespace App\Application\Actions\Author;

use App\Application\Actions\Action;
use App\Domain\Author\AuthorRepository;
use Psr\Log\LoggerInterface;

abstract class AuthorAction extends Action
{
    /**
     * @var AuthorRepository
     */
    protected $authorRepository;

    /**
     * @param LoggerInterface $logger
     * @param AuthorRepository  $authorRepository
     */
    public function __construct(LoggerInterface $logger, AuthorRepository $authorRepository)
    {
        parent::__construct($logger);
        $this->authorRepository = $authorRepository;
    }
}
