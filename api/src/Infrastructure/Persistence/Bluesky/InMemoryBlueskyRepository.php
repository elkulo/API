<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Bluesky;

use App\Application\Settings\SettingsInterface;
use App\Domain\Bluesky\Bluesky;
use App\Domain\Bluesky\BlueskyNotFoundException;
use App\Domain\Bluesky\BlueskyRepository;

class InMemoryBlueskyRepository implements BlueskyRepository
{
    /**
     * @var Bluesky[]
     */
    private array $blueskys;

    public function __construct(SettingsInterface $settings)
    {
        $blueskys = [];

        // 設定値.
        $username = $settings->get('bluesky.username');
        $password = $settings->get('bluesky.password');

        // JSON Web Tokenを取得.
        $token = $this->getBlueskyToken($username, $password);
        if ($token) {
            // タイムラインリクエスト.
            $response = $this->getBlueskyTimeline($username, $token);
            if (isset($response['feed']) && $response['feed']) {
                $data = $response['feed'];
                $max = count($data);
                for ($i = 1; $i <= $max; $i++) {
                    if (isset($data[$i - 1])) {
                        $blueskys[$i] = new Bluesky($i, $data[$i - 1]['post']);
                    }
                }
            }
        }

        $this->blueskys = $blueskys;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->blueskys);
    }

    /**
     * {@inheritdoc}
     */
    public function findBlueskyOfId(int $id): Bluesky
    {
        if (!isset($this->blueskys[$id])) {
            throw new BlueskyNotFoundException();
        }

        return $this->blueskys[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function findBlueskyOfUser(): array
    {
        if (!isset($this->blueskys['1'])) {
            throw new BlueskyNotFoundException();
        }

        return $this->blueskys['1']->getUser();
    }

    /**
     * getBlueskyToken
     *
     * @param  string $username
     * @param  string $password
     * @return mixed
     */
    private function getBlueskyToken(string $username, string $password): mixed
    {

        $ch = curl_init('https://bsky.social/xrpc/com.atproto.server.createSession');
        curl_setopt_array($ch, [
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'identifier' => $username,
                'password' => $password,
            ]),
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $responseJson = json_decode($response, true);

        return isset($responseJson['accessJwt']) ? $responseJson['accessJwt'] : '';
    }

    /**
     * getBlueskyTimeline
     *
     * @param  string $username
     * @param  string $token
     * @return mixed
     */
    private function getBlueskyTimeline(string $username, string $token): mixed
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => sprintf(
                'https://bsky.social/xrpc/app.bsky.feed.getAuthorFeed?actor=%1$s&filter=posts_no_replies',
                $username
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                "Authorization: Bearer {$token}"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $responseJson = json_decode($response, true);

        return $responseJson;
    }
}
