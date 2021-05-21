<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Product;

use App\Domain\Product\Product;
use App\Domain\Product\ProductNotFoundException;
use App\Domain\Product\ProductRepository;

class InMemoryProductRepository implements ProductRepository
{
    /**
     * @var Product[]
     */
    private $products;

    /**
     * InMemoryProductRepository constructor.
     *
     * @param array|null $products
     */
    public function __construct(array $products = null)
    {
        if (is_readable(PRODUCT_DB)) {
            $json = file_get_contents(PRODUCT_DB);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $data = json_decode($json, true);
            for ($i = 1; $i <= count($data); $i++) {
                if (isset($data[$i - 1])) {
                    $data[$i - 1]['id'] = (int) $data[$i - 1]['id'];
                    $products[$i] = new Product($i, $data[$i - 1]);
                }
            }
        }

        $this->products = $products ?? [
            1 => new Product(1, []),
            2 => new Product(2, []),
            3 => new Product(3, []),
            4 => new Product(4, []),
            5 => new Product(5, []),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->products);
    }

    /**
     * {@inheritdoc}
     */
    public function findProductOfId(int $id): Product
    {
        if (!isset($this->products[$id])) {
            throw new ProductNotFoundException();
        }

        return $this->products[$id];
    }
}
