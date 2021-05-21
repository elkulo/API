<?php

declare(strict_types=1);

namespace Tests\Domain\User;

use App\Domain\User\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function userProvider()
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
     * @dataProvider userProvider
     * @param $id
     * @param $data
     */
    public function testGetters($id, $data)
    {
        $user = new User($id, $data);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($data, $user->getData());
    }

    /**
     * @dataProvider userProvider
     * @param $id
     * @param $data
     */
    public function testJsonSerialize($id, $data)
    {
        $user = new User($id, $data);

        $expectedPayload = json_encode([
            'id' => $id,
            'data' => $data,
        ]);

        $this->assertEquals($expectedPayload, json_encode($user));
    }
}
