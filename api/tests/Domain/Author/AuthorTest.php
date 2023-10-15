<?php

declare(strict_types=1);

namespace Tests\Domain\Author;

use App\Domain\Author\Author;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    public function authorProvider()
    {
        return [
            [1, []],
            [2, []],
            [3, []],
            [4, []],
            [5, []],
        ];
    }

    /**
     * @dataProvider authorProvider
     * @param $id
     * @param $data
     */
    public function testGetters($id, $data)
    {
        $author = new Author($id, $data);

        $this->assertEquals($id, $author->getId());
        $this->assertEquals($data, $author->getData());
    }

    /**
     * @dataProvider authorProvider
     * @param $id
     * @param $data
     */
    public function testJsonSerialize($id, $data)
    {
        $author = new Author($id, $data);

        $expectedPayload = json_encode([
            'id' => $id,
        ]);

        $this->assertEquals($expectedPayload, json_encode($author));
    }
}
