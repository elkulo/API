<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;
use Dotenv\Dotenv;

return function (ContainerBuilder $containerBuilder) {

    // Dotenv
    $env_dir = __DIR__ . '/../.env';
    try {
        if (is_readable($env_dir)) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();
        } else {
            throw new Exception('環境設定ファイルがありません');
        }
    } catch (Exception $e) {
        exit($e->getMessage());
    }

    // Timezone
    if (isset($_ENV['TIME_ZONE'])) {
        date_default_timezone_set($_ENV['TIME_ZONE']);
    }

    // Defined
    // phpcs:disable
    defined('API_KEY') || define('API_KEY', md5( date('Ymd').$_ENV['API_SALT'] ));
    defined('POSTS_DB') || define('POSTS_DB', __DIR__ . '/../' . $_ENV['POSTS_DB']);
    defined('USERS_DB') || define('USERS_DB', __DIR__ . '/../' . $_ENV['USERS_DB']);
    // phpcs:enable

    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'debug' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] : false,
            'displayErrorDetails' => true, // Should be set to false in production
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app-' . date("Y-m-d") . '.log',
                'level' => Logger::DEBUG,
            ],
            'twig' => [
                'debug' => isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] : false,
                'strict_variables' => true,
                'cache' => __DIR__ . '/../var/cache/twig',
            ],
        ],
    ]);
};
