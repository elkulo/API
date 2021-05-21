<?php
declare(strict_types=1);

namespace App\Domain\Author;

use App\Domain\DomainException\DomainRecordNotFoundException;

class AuthorNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The user you requested does not exist.';
}
