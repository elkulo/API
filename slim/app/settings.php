<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Level;
use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            $log_file = isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app-' . date("Y-m-d") . '.log';
            return new Settings([
                // Should be set to false in production.
                'displayErrorDetails' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'true' : false,
                'logError'            => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'false' : true,
                'logErrorDetails'     => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'false' : true,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => $log_file,
                    'level' => Level::Debug,
                ],
                'twig' => [
                    'debug' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'true' : false,
                    'auto_reload' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'true' : false,
                    'strict_variables' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'true' : false,
                    'cache' => __DIR__ . '/../var/cache/twig',
                ],
                'site.name' => isset($_ENV['SITE_NAME']) ? $_ENV['SITE_NAME'] : '',
                'site.url' => (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . rtrim($_SERVER['HTTP_HOST'], '/'),
                'site.timezone' => isset($_ENV['TIME_ZONE']) ? $_ENV['TIME_ZONE'] : 'UTC',
                'debug' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'true' : false,
                'api.keys' => [
                    md5(date('YmdHi') . $_ENV['API_KEY'] . $_ENV['API_SALT']),
                    md5(( date('YmdHi') - 1 ) . $_ENV['API_KEY'] . $_ENV['API_SALT']),
                ],
                'author.src' => __DIR__ . '/../../' . trim($_ENV['AUTHOR_SOURCE'], '/'),
                'product.src' => __DIR__ . '/../../' . trim($_ENV['PRODUCT_SOURCE'], '/'),
            ]);
        }
    ]);
};
