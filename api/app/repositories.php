<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use App\Domain\Author\AuthorRepository;
use App\Infrastructure\Persistence\Author\InMemoryAuthorRepository;
use App\Domain\Product\ProductRepository;
use App\Infrastructure\Persistence\Product\InMemoryProductRepository;
use App\Domain\Bluesky\BlueskyRepository;
use App\Infrastructure\Persistence\Bluesky\InMemoryBlueskyRepository;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([

        // Here we map our AuthorRepository interface to its in memory implementation
        AuthorRepository::class => \DI\autowire(InMemoryAuthorRepository::class),

        // Here we map our ProductRepository interface to its in memory implementation
        ProductRepository::class => \DI\autowire(InMemoryProductRepository::class),

        // Here we map our BlueskyRepository interface to its in memory implementation
        BlueskyRepository::class => \DI\autowire(InMemoryBlueskyRepository::class),
    ]);
};
