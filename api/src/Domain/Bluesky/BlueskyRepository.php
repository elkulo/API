<?php
declare(strict_types=1);

namespace App\Domain\Bluesky;

interface BlueskyRepository
{
    /**
     * @return Bluesky[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Bluesky
     * @throws BlueskyNotFoundException
     */
    public function findBlueskyOfId(int $id): Bluesky;

    /**
     * @return mixed
     */
    public function findBlueskyOfUser(): mixed;
}
