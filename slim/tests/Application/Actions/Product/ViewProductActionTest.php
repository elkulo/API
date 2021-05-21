<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Product;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Product\Product;
use App\Domain\Product\ProductNotFoundException;
use App\Domain\Product\ProductRepository;
use DI\Container;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

class ViewProductActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $product = new Product(1, []);

        $productRepositoryProphecy = $this->prophesize(ProductRepository::class);
        $productRepositoryProphecy
            ->findProductOfId(1)
            ->willReturn($product)
            ->shouldBeCalledOnce();

        $container->set(ProductRepository::class, $productRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/products/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $product);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsProductNotFoundException()
    {
        $app = $this->getAppInstance();

        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $app->add($errorMiddleware);

        /** @var Container $container */
        $container = $app->getContainer();

        $productRepositoryProphecy = $this->prophesize(ProductRepository::class);
        $productRepositoryProphecy
            ->findProductOfId(1)
            ->willThrow(new ProductNotFoundException())
            ->shouldBeCalledOnce();

        $container->set(ProductRepository::class, $productRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/products/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'The product you requested does not exist.');
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
