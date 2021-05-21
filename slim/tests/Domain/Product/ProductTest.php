<?php
declare(strict_types=1);

namespace Tests\Domain\Product;

use App\Domain\Product\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function productProvider()
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
     * @dataProvider productProvider
     * @param $id
     * @param $data
     */
    public function testGetters($id, $data)
    {
        $product = new Product($id, $data);

        $this->assertEquals($id, $product->getId());
        $this->assertEquals($data, $product->getData());
    }

    /**
     * @dataProvider productProvider
     * @param $id
     * @param $data
     */
    public function testJsonSerialize($id, $data)
    {
        $product = new Product($id, $data);

        $expectedPayload = json_encode([
            'id' => $id,
            'data' => $data,
        ]);

        $this->assertEquals($expectedPayload, json_encode($product));
    }
}
