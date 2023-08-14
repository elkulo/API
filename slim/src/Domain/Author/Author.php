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
        // $dataにIDがあればそちらを使用
        if (! isset($this->data['id'])) {
            $this->data['id'] = $this->id;
        }
        return $this->data;
    }
}
