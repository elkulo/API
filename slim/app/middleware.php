<?php

declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $app->add(SessionMiddleware::class);

    // Whoops.
    $c = $app->getContainer();
    if ((bool)($c->get('settings')['debug'] ?? false)) {
        $app->add(new WhoopsMiddleware(['enable' => true]));
    } else {
        $errorMiddleware = $app->addErrorMiddleware(false, true, true);
        $errorHandler    = $errorMiddleware->getDefaultErrorHandler();
        $errorHandler->registerErrorRenderer('text/html', HtmlErrorRenderer::class);
    }

    // Twig.
    $app->add(SessionMiddleware::class);
    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));
};
