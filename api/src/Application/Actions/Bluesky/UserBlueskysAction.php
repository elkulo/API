<?php
declare(strict_types=1);

namespace App\Application\Actions\Bluesky;

use Psr\Http\Message\ResponseInterface as Response;

class UserBlueskysAction extends BlueskyAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $blueskys = $this->blueskyRepository->findBlueskyOfUser();

        $this->logger->info('Blueskys user was viewed.');

        return $this->respondWithData($blueskys);
    }
}
