<?php
declare(strict_types=1);

namespace App\Application\Actions\Bluesky;

use App\Application\Actions\Action;
use App\Domain\Bluesky\BlueskyRepository;
use Psr\Log\LoggerInterface;

abstract class BlueskyAction extends Action
{
    protected BlueskyRepository $blueskyRepository;

    public function __construct(LoggerInterface $logger, BlueskyRepository $blueskyRepository)
    {
        parent::__construct($logger);
        $this->blueskyRepository = $blueskyRepository;
    }
}
