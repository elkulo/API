<?php
declare(strict_types=1);

namespace App\Domain\Author;

use JsonSerializable;

class Author implements JsonSerializable
{
    private ?int $id;

    private array $data;

    public function __construct(?int $id, array $data)
    {
        $this->id = $id;
        $data['id'] = $id;

        // IDを付与.
        if (isset($data['id'])) {
            $this->id = (int) $id;
            $data['id'] = (int) $data['id'];
        }
        $this->data = $data;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): mixed
    {
        return $this->data;
    }
}
