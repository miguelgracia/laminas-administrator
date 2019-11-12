<?php

namespace AmYouTube\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class YoutubeService implements FactoryInterface
{
    protected $serviceLocator;
    protected $sessionService;
    protected $controllerPluginManager;

    protected $OAUTH2_CLIENT_ID = '934446495538-k83kn2hhivvdm15um1j58srr0sc0r35l.apps.googleusercontent.com';
    protected $OAUTH2_CLIENT_SECRET = 'gEwlNEz_ItJEYMUvoBQV88n-';

    protected $youtube;
    protected $client;
    protected $token;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->serviceLocator = $container;
        $this->sessionService = $container->get('Administrator\Service\SessionService');
        $this->controllerPluginManager = $this->serviceLocator->get('ControllerPluginManager');

        $urlPlugin = $this->controllerPluginManager->get('url');

        /*
         * You can acquire an OAuth 2.0 client ID and client secret from the
         * Google Developers Console <https://console.developers.google.com/>
         * For more information about using OAuth 2.0 to access Google APIs, please see:
         * <https://developers.google.com/youtube/v3/guides/authentication>
         * Please ensure that you have enabled the YouTube Data API for your project.
         */

        $this->client = new \Google_Client();
        $this->client->setClientId($this->OAUTH2_CLIENT_ID);
        $this->client->setClientSecret($this->OAUTH2_CLIENT_SECRET);
        $this->client->setScopes('https://www.googleapis.com/auth/youtube');

        $redirect = $urlPlugin->fromRoute('administrator',array(
            'module' => 'you-tube',
            'action' => 'oauth-callback'
        ),array(
            'force_canonical' => true
        ));

        $this->client->setRedirectUri($redirect);

        // Define an object that will be used to make all API requests.
        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }


    public function getYoutubeService()
    {
        return new \Google_Service_YouTube($this->client);
    }

    /**
     * Acceso a los videos de youtube que tenemos registrados en base de datos.
     * De esta forma ahorramos en llamadas a la api y en problemas surgidos a raiz
     * de estar o no estar loqueado con los servicios de youtube
     */
    public function getVideosInDatabase()
    {
        $table = $this->serviceLocator->get('AmYouTube\Model\YouTubeTable');

        return $table->all();
    }
}