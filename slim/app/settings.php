<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;
use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            $log_file = isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app-' . date("Y-m-d") . '.log';
            return new Settings([
                'site.url' => (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . rtrim($_SERVER['HTTP_HOST'], '/'),
                'debug' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'true' : false,
                // Should be set to false in production.
                'displayErrorDetails' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'true' : false,
                'logError'            => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'false' : true,
                'logErrorDetails'     => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'false' : true,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => $log_file,
                    'level' => Logger::DEBUG,
                ],
                'twig' => [
                    'debug' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] : false,
                    'strict_variables' => true,
                    'cache' => __DIR__ . '/../var/cache/twig',
                    'auto_reload' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'true' : false,
                    'strict_variables' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] === 'true' : false,
                ],
                'api.key' => md5(date('Ymd').$_ENV['API_SALT']),
                'author.src' => __DIR__ . '/../' . $_ENV['AUTHOR_SOURCE'],
                'product.src' => __DIR__ . '/../' . $_ENV['PRODUCT_SOURCE'],
            ]);
        }
    ]);
};
