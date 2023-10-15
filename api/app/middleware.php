<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use App\Application\Settings\SettingsInterface;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Middlewares\Whoops;

return function (App $app) {
    $app->add(SessionMiddleware::class);

    // Whoops.
    if ($app->getContainer()->get(SettingsInterface::class)->get('debug')) {
        $app->add(Whoops::class);
    }

    // Twig.
    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));
};
