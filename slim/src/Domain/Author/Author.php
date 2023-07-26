<?php
declare(strict_types=1);

namespace App\Domain\Author;

use JsonSerializable;

class Author implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var array
     */
    private $data;

    /**
     * @param int|null  $id
     * @param array     $data
     */
    public function __construct(?int $id, array $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        // $dataにIDがあればそちらを使用
        if (! isset($this->data['id'])) {
            $this->data['id'] = $this->id;
        }
        return $this->data;
    }
}
