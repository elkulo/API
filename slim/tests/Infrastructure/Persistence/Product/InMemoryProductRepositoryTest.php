<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Product;

use App\Domain\Product\Product;
use App\Domain\Product\ProductNotFoundException;
use App\Infrastructure\Persistence\Product\InMemoryProductRepository;
use Tests\TestCase;

class InMemoryProductRepositoryTest extends TestCase
{
    public function testFindAll(InMemoryProductRepository $productRepository)
    {
        $product = new Product(1, []);

        $this->assertEquals([$product], $productRepository->findAll());
    }

    public function testFindAllProductsByDefault(InMemoryProductRepository $productRepository)
    {
        $products = [
            1 => new Product(1, []),
            2 => new Product(2, []),
            3 => new Product(3, []),
            4 => new Product(4, []),
            5 => new Product(5, []),
        ];

        $this->assertEquals(array_values($products), $productRepository->findAll());
    }

    public function testFindProductOfId(InMemoryProductRepository $productRepository)
    {
        $product = new Product(1, []);

        $this->assertEquals($product, $productRepository->findProductOfId(1));
    }

    public function testFindProductOfIdThrowsNotFoundException(InMemoryProductRepository $productRepository)
    {
        $this->expectException(ProductNotFoundException::class);
        $productRepository->findProductOfId(1);
    }
}
