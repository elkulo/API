<?php
declare(strict_types=1);

namespace App\Domain\Bluesky;

use App\Domain\DomainException\DomainRecordNotFoundException;

class BlueskyNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The user you requested does not exist.';
}
