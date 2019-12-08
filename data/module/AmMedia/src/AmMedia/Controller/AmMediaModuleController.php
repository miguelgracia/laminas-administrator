<?php

namespace AmMedia\Controller;

use Administrator\Controller\AuthController;
use AmMedia\Filter\VideoValidator;
use Zend\Filter\BaseName;
use Zend\Filter\Dir;
use Zend\Filter\RealPath;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AmMediaModuleController extends AuthController
{
    protected $config;

    /**
     * @return array|\Zend\View\Model\ViewModel
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
        $scanDirService = $this->serviceLocator->get('AmMedia\Service\ScanDirService');

        $scanDirService->scan([
            $_SERVER['DOCUMENT_ROOT'] . '/media',
        ]);

        $videos = $scanDirService->getFiles([
            'video/mp4',
            'application/octet-stream'
        ]);

        return compact('videos');
    }

    public function savePosterAction()
    {
        $imageManager = $this->serviceLocator->get('AmMedia\Service\InterventionImageService');
        $dirFilter = new Dir();
        $baseNameFilter = new BaseName();

        $video = $this->params()->fromPost('video');
        $videoPath = $this->params()->fromPost('path');

        $basePath = $dirFilter->filter($videoPath);
        $baseName = $baseNameFilter->filter($videoPath);

        $image = $imageManager->make($video);

        $imageToSave = $_SERVER['DOCUMENT_ROOT'] . $basePath . '/' . 'video-poster-' . md5($baseName) . '.jpg';

        $image->save($imageToSave, 80);

        return new JsonModel([
            'result' => true
        ]);
    }

    public function connectorAction()
    {
        $fileManager = $this->serviceLocator->get('AmMedia\FileManager\FileManagerService');
        $fileManagerResponse = $fileManager->handleRequest();

        $controllerResponse = $this->getResponse();
        $controllerResponse->setContent($fileManagerResponse->getContent());

        $fileManagerHeaders = $fileManagerResponse->getHeaders()->toArray();

        $controllerHeaders = $controllerResponse->getHeaders();
        $controllerHeaders->clearHeaders()->addHeaders($fileManagerHeaders);

        return $controllerResponse;
    }
}
