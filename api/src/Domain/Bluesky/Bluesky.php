<?php

declare(strict_types=1);

namespace App\Domain\Bluesky;

use JsonSerializable;

class Bluesky implements JsonSerializable
{
    private ?int $id;

    private array $data;

    public function __construct(?int $id, array $feed)
    {
        // 初期値.
        $data = array(
            'id' => $id,
            'author' => array(
                'handle' => '',
                'displayName' => '',
                'url' => '',
            ),
            'context' => '',
            'createdAt' => '',
            'uri' => '',
            '__feed' => $feed,
        );

        // AuthorのURLを追加.
        if (isset($feed['author']['handle'])) {
            $handle = $feed['author']['handle'];
            $data['author']['handle'] = $handle;
            $data['author']['url'] = $this->getProfileURL($handle);

            // AuthorにDisplayNameを追加.
            if (isset($feed['author']['displayName'])) {
                $displayName = $feed['author']['displayName'];
                $data['author']['displayName'] = $displayName ? $displayName : $feed['author']['handle'];
            }

            // uriを追加.
            if (isset($feed['uri'])) {
                $arr_uri = explode('/', $feed['uri']);
                $post_uri = end($arr_uri);
                $data['uri'] = $this->getProfileURL($handle . '/post/' . $post_uri);
            }
        }

        // contextを追加.
        if (isset($feed['record']['text'])) {
            $data['context'] = $feed['record']['text'];
        }

        // createdAtを追加.
        if (isset($feed['record']['createdAt'])) {
            $data['createdAt'] = date('Y-m-d H:i:s', strtotime($feed['record']['createdAt']));
        }

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
        return $this->data;
    }

    /**
     * get_user
     *
     * @return array
     */
    public function getUser(): array
    {
        // 初期値.
        $user = array(
            'handle' => '',
            'displayName' => '',
            'url' => '',
        );

        // 最初の値を起点.
        $data = $this->data;

        // UserのURLを追加.
        if (isset($data['author']['handle'])) {
            $handle = $data['author']['handle'];
            $user['handle'] = $handle;
            $user['url'] = $this->getProfileURL($handle);

            // UserにDisplayNameを追加.
            if (isset($data['author']['displayName'])) {
                $displayName = $data['author']['displayName'];
                $user['displayName'] = $displayName ? $displayName : $data['author']['handle'];
            }
        }
        return $user;
    }

    /**
     * get_profile
     *
     * @param  string $text
     * @return string
     */
    private function getProfileURL(string $text): string
    {
        return sprintf('https://bsky.app/profile/%1$s', $text);
    }
}
