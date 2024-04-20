<?php
declare(strict_types=1);

namespace App\Application\Actions\Bluesky;

use Psr\Http\Message\ResponseInterface as Response;

class ViewBlueskyAction extends BlueskyAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $blueskyId = (int) $this->resolveArg('id');
        $bluesky = $this->blueskyRepository->findBlueskyOfId($blueskyId);

        $this->logger->info('Bluesky of id ' . $blueskyId . ' was viewed.');

        return $this->respondWithData($bluesky);
    }
}
