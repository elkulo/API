<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Product;

use App\Application\Actions\ActionPayload;
use App\Domain\Product\ProductRepository;
use App\Domain\Product\Product;
use DI\Container;
use Tests\TestCase;

class ListProductActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $product = new Product(1, []);

        $productRepositoryProphecy = $this->prophesize(ProductRepository::class);
        $productRepositoryProphecy
            ->findAll()
            ->willReturn([$product])
            ->shouldBeCalledOnce();

        $container->set(ProductRepository::class, $productRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/products');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [$product]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
