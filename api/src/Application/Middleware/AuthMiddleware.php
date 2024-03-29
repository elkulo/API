<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Slim\Psr7\Response as ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;
use App\Application\Settings\SettingsInterface;

class AuthMiddleware implements Middleware
{

    protected LoggerInterface $logger;

    protected SettingsInterface $settings;

    public function __construct(LoggerInterface $logger, SettingsInterface $settings)
    {
        $this->logger = $logger;
        $this->settings = $settings;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);

        $api_keys = $this->settings->get('api.keys');

        $referer = isset($_SERVER['HTTP_REFERER'])? htmlspecialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES, 'UTF-8'): '';

        if (!in_array(filter_input(INPUT_GET, 'key', FILTER_SANITIZE_ENCODED), $api_keys)
            && strpos($referer, $this->settings->get('site.url')) === false
        ) {
            $this->logger->info('401 Unauthorized.');
            $response = new ResponseFactory();
            $json = json_encode([
                'statusCode' => 401,
                'error' => [
                    'type' => 'UNAUTHENTICATED',
                    'description' => 'The request requires valid user authentication.'
                ]
            ], JSON_PRETTY_PRINT);
            $response->getBody()->write($json);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
        return $response;
    }
}
