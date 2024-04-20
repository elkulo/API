<?php
declare(strict_types=1);

namespace App\Application\Actions\Bluesky;

use Psr\Http\Message\ResponseInterface as Response;

class ListBlueskysAction extends BlueskyAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $blueskys = $this->blueskyRepository->findAll();

        $this->logger->info('Blueskys list was viewed.');

        return $this->respondWithData($blueskys);
    }
}
