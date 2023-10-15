<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Author;

use App\Application\Actions\ActionPayload;
use App\Domain\Author\AuthorRepository;
use App\Domain\Author\Author;
use DI\Container;
use Tests\TestCase;

class ListAuthorActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $author = new Author(1, []);

        $authorRepositoryProphecy = $this->prophesize(AuthorRepository::class);
        $authorRepositoryProphecy
            ->findAll()
            ->willReturn([$author])
            ->shouldBeCalledOnce();

        $container->set(AuthorRepository::class, $authorRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/authors');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [$author]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
