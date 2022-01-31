<?php
declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;

class AuthAuthentication extends Action
{

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        parent::__construct($logger);
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $this->logger->info('401 Unauthorized.');

        return $this->respondWithData([
            'error' => [
                'type' => 'UNAUTHENTICATED',
                'description' => 'The request requires valid user authentication.'
            ]
        ], 401);
    }
}
