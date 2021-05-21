<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use App\Domain\Author\AuthorRepository;
use App\Infrastructure\Persistence\Author\InMemoryAuthorRepository;
use App\Domain\Product\ProductRepository;
use App\Infrastructure\Persistence\Product\InMemoryProductRepository;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our AuthorRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        AuthorRepository::class => \DI\autowire(InMemoryAuthorRepository::class),
    ]);

    // Here we map our ProductRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        ProductRepository::class => \DI\autowire(InMemoryProductRepository::class),
    ]);
};
