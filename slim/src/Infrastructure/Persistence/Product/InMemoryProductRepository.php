<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Product;

use App\Application\Settings\SettingsInterface;
use App\Domain\Product\Product;
use App\Domain\Product\ProductNotFoundException;
use App\Domain\Product\ProductRepository;

class InMemoryProductRepository implements ProductRepository
{
    /**
     * @var Product[]
     */
    private array $products;

    public function __construct(SettingsInterface $settings)
    {
        $products = [];

        $src = $settings->get('product.src');

        if (is_readable($src)) {
            $json = file_get_contents($src);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $data = json_decode($json, true);
            $max = count($data);
            for ($i = 1; $i <= $max; $i++) {
                if (isset($data[$i - 1])) {
                    $data[$i - 1]['id'] = (int) $data[$i - 1]['id'];
                    $products[$i] = new Product($i, $data[$i - 1]);
                }
            }
        }

        $this->products = $products;
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
