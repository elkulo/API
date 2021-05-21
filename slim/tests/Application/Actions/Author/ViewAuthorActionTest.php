<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Author;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Author\Author;
use App\Domain\Author\AuthorNotFoundException;
use App\Domain\Author\AuthorRepository;
use DI\Container;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

class ViewAuthorActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $author = new Author(1, []);

        $authorRepositoryProphecy = $this->prophesize(AuthorRepository::class);
        $authorRepositoryProphecy
            ->findAuthorOfId(1)
            ->willReturn($author)
            ->shouldBeCalledOnce();

        $container->set(AuthorRepository::class, $authorRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/authors/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $author);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsAuthorNotFoundException()
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

        $authorRepositoryProphecy = $this->prophesize(AuthorRepository::class);
        $authorRepositoryProphecy
            ->findAuthorOfId(1)
            ->willThrow(new AuthorNotFoundException())
            ->shouldBeCalledOnce();

        $container->set(AuthorRepository::class, $authorRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/authors/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'The author you requested does not exist.');
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
