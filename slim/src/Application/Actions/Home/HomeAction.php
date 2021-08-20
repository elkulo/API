<?php
declare(strict_types=1);

namespace App\Application\Actions\Home;

use App\Application\Settings\SettingsInterface;
use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class HomeAction extends Action
{

    /**
     * @var SettingsInterface
     */
    protected $settings;

    /**
     * @var Twig
     */
    protected $twig;

    /**
     * @param LoggerInterface $logger
     * @param SettingsInterface $settings
     * @param Twig $twig
     */
    public function __construct(LoggerInterface $logger, SettingsInterface $settings, Twig $twig)
    {
        parent::__construct($logger);
        $this->settings = $settings;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $this->logger->info('Home was viewed.');

        $twig = $this->twig;
        $settings = $this->settings;

        // API KEY.
        $api_key = $settings->get('api.key');

        // Root.
        $home_url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . rtrim($_SERVER['HTTP_HOST'], '/');

        $response = $twig->render($this->response, 'home.twig', [
            'title' => isset($_ENV['SITE_NAME']) ? $_ENV['SITE_NAME'] : 'API Server',
            'description' => 'The RESTful API with slim framework.',
            'home_url' => $home_url,
            'api_key' => $api_key
        ]);

        return $response;
    }
}
