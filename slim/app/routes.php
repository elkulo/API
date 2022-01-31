<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Application\Actions\Home\HomeAction;
use App\Application\Actions\Auth\AuthAuthentication;
use App\Application\Actions\Author\ListAuthorsAction;
use App\Application\Actions\Author\ViewAuthorAction;
use App\Application\Actions\Product\ListProductsAction;
use App\Application\Actions\Product\ViewProductAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    // Home Page
    $app->get('/', HomeAction::class);

    // Author API
    $app->group('/author', function (Group $group) use ($app) {
        // API KEY.
        $api_key = $app->getContainer()->get(SettingsInterface::class)->get('api.key');
        if (isset($_GET['key']) && $api_key === htmlspecialchars($_GET['key'], ENT_QUOTES, 'UTF-8')) {
            $group->get('', ListAuthorsAction::class);
            $group->get('/{id}', ViewAuthorAction::class);
        } else {
            $group->get('', AuthAuthentication::class);
            $group->get('/{id}', AuthAuthentication::class);
        }
    });

    // Product API
    $app->group('/product', function (Group $group) use ($app) {
        // API KEY.
        $api_key = $app->getContainer()->get(SettingsInterface::class)->get('api.key');
        if (isset($_GET['key']) && $api_key === htmlspecialchars($_GET['key'], ENT_QUOTES, 'UTF-8')) {
            $group->get('', ListProductsAction::class);
            $group->get('/{id}', ViewProductAction::class);
        } else {
            $group->get('', AuthAuthentication::class);
            $group->get('/{id}', AuthAuthentication::class);
        }
    });
};
