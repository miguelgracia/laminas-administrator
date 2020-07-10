<?php

namespace AmMedia\Controller;

use Administrator\Controller\AuthController;
use AmMedia\FileManager\FileManagerService;
use AmMedia\Service\InterventionImageService;
use AmMedia\Service\ScanDirService;
use Laminas\Filter\BaseName;
use Laminas\Filter\Dir;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\JsonModel;

class AmMediaModuleController extends AuthController
{
    protected $scanDirService;
    protected $interventionImageService;
    protected $fileManagerService;

    public function onDispatch(MvcEvent $e)
    {
        $serviceLocator = $e->getApplication()->getServiceManager();

        $this->scanDirService = $serviceLocator->get(ScanDirService::class);
        $this->interventionImageService = $serviceLocator->get(InterventionImageService::class);
        $this->fileManagerService = $serviceLocator->get(FileManagerService::class);

        return parent::onDispatch($e);
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $queryParams = $this->getRequest()->getQuery();

        if ($queryParams->modal == 'on') {
            $this->layout()->setTemplate('layout/filemanager-layout');
        }
    }

    public function videoPosterAction()
    {
        $this->scanDirService->scan([
            $_SERVER['DOCUMENT_ROOT'] . '/media',
        ]);

        $videos = $this->scanDirService->getFiles([
            'video/mp4',
            'application/octet-stream'
        ]);

        return compact('videos');
    }

    public function savePosterAction()
    {
        $video = $this->params()->fromPost('video');
        $videoPath = $this->params()->fromPost('path');

        $basePath = (new Dir)->filter($videoPath);
        $baseName = (new BaseName)->filter($videoPath);

        $imageToSave = $_SERVER['DOCUMENT_ROOT'] . $basePath . '/' . 'video-poster-' . md5($baseName) . '.jpg';

        $this->interventionImageService
            ->make($video)
            ->save($imageToSave, 80);

        return new JsonModel([
            'result' => true
        ]);
    }

    public function connectorAction()
    {
        $fileManagerResponse = $this->fileManagerService->handleRequest($this->request);

        $controllerResponse = $this->getResponse()->setContent($fileManagerResponse->getContent());

        $fileManagerHeaders = $fileManagerResponse->getHeaders()->toArray();

        $controllerHeaders = $controllerResponse->getHeaders();
        $controllerHeaders->clearHeaders()->addHeaders($fileManagerHeaders);

        return $controllerResponse;
    }
}
