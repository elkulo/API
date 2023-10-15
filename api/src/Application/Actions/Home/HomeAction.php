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

    protected SettingsInterface $settings;

    protected Twig $view;

    public function __construct(LoggerInterface $logger, SettingsInterface $settings, Twig $twig)
    {
        parent::__construct($logger);
        $this->settings = $settings;
        $this->view = $twig;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $this->logger->info('Home was viewed.');

        return $this->view->render($this->response, 'home.twig', [
            'title' => isset($_ENV['SITE_NAME']) ? $_ENV['SITE_NAME'] : 'API Server',
            'description' => 'The RESTful API with slim framework.',
            'home_url' => $this->settings->get('site.url'),
            'api_key' => isset($this->settings->get('api.keys')[0]) ? $this->settings->get('api.keys')[0]: '',
        ]);
    }
}
