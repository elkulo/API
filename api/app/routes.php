<?php
declare(strict_types=1);

use App\Application\Actions\Home\HomeAction;
use App\Application\Actions\Author\ListAuthorsAction;
use App\Application\Actions\Author\ViewAuthorAction;
use App\Application\Actions\Product\ListProductsAction;
use App\Application\Actions\Product\ViewProductAction;
use App\Application\Middleware\AuthMiddleware as Auth;
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
    $app->group('/author', function (Group $group) {
        $group->get('', ListAuthorsAction::class);
        $group->get('/{id}', ViewAuthorAction::class);
    })->add(Auth::class);

    // Product API
    $app->group('/product', function (Group $group) {
        $group->get('', ListProductsAction::class);
        $group->get('/{id}', ViewProductAction::class);
    })->add(Auth::class);
};
