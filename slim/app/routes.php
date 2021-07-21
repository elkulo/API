<?php

declare(strict_types=1);

use App\Application\Actions\Author\ListAuthorsAction;
use App\Application\Actions\Author\ViewAuthorAction;
use App\Application\Actions\Product\ListProductsAction;
use App\Application\Actions\Product\ViewProductAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Views\Twig;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) use ($app) {
        $twig = $app->getContainer()->get(Twig::class);

        $api_key = $app->getContainer()->get('settings')['api.key'];

        $home_url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . rtrim($_SERVER['HTTP_HOST'], '/');

        return $twig->render($response, 'home.twig', [
            'title' => isset($_ENV['SITE_NAME'])? $_ENV['SITE_NAME']: 'API Server',
            'description' => 'The RESTful API with slim framework.',
            'home_url' => $home_url,
            'api_key' => $api_key
        ]);
    });

    // API KEY.
    $api_key = $app->getContainer()->get('settings')['api.key'];

    if (isset($_GET['key']) && $api_key === htmlspecialchars($_GET['key'], ENT_QUOTES, 'UTF-8')) {
        $app->group('/author', function (Group $group) {
            $group->get('', ListAuthorsAction::class);
            $group->get('/{id}', ViewAuthorAction::class);
        });

        $app->group('/product', function (Group $group) {
            $group->get('', ListProductsAction::class);
            $group->get('/{id}', ViewProductAction::class);
        });
    }
};
