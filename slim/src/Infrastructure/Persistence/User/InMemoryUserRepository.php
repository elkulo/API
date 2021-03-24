<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private $users;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param array|null $users
     */
    public function __construct(array $users = null)
    {
        if (is_readable(USERS_DB)) {
            $json = file_get_contents(USERS_DB);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $data = json_decode($json, true);
            for ($i = 1; $i <= count($data); $i++) {
                if (isset($data[$i - 1])) {
                    $users[$i] = new User(
                        (int) $data[$i - 1]['id'],
                        $data[$i - 1]['username'],
                        $data[$i - 1]['firstName'],
                        $data[$i - 1]['lastName'],
                        $data[$i - 1]['description']
                    );
                }
            }
        }

        $this->users = $users ?? [
            1 => new User(1, 'bill.gates', 'Bill', 'Gates', 'description'),
            2 => new User(2, 'steve.jobs', 'Steve', 'Jobs', 'description'),
            3 => new User(3, 'mark.zuckerberg', 'Mark', 'Zuckerberg', 'description'),
            4 => new User(4, 'evan.spiegel', 'Evan', 'Spiegel', 'description'),
            5 => new User(5, 'jack.dorsey', 'Jack', 'Dorsey', 'description'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException();
        }

        return $this->users[$id];
    }
}
