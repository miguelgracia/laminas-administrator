<?php

namespace AmMedia\Controller;

use Administrator\Controller\AuthController;
use AmMedia\FileManager\FileManagerService;
use AmMedia\Service\InterventionImageService;
use AmMedia\Service\ScanDirService;
use Zend\Filter\BaseName;
use Zend\Filter\Dir;
use Zend\View\Model\JsonModel;

class AmMediaModuleController extends AuthController
{
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
        $scanDirService = $this->serviceLocator->get(ScanDirService::class);

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
        $video = $this->params()->fromPost('video');
        $videoPath = $this->params()->fromPost('path');

        $basePath = (new Dir)->filter($videoPath);
        $baseName = (new BaseName)->filter($videoPath);

        $imageToSave = $_SERVER['DOCUMENT_ROOT'] . $basePath . '/' . 'video-poster-' . md5($baseName) . '.jpg';

        $this->serviceLocator
            ->get(InterventionImageService::class)
            ->make($video)
            ->save($imageToSave, 80);

        return new JsonModel([
            'result' => true
        ]);
    }

    public function connectorAction()
    {
        $fileManagerResponse = $this->serviceLocator->get(FileManagerService::class)->handleRequest();

        $controllerResponse = $this->getResponse()->setContent($fileManagerResponse->getContent());

        $fileManagerHeaders = $fileManagerResponse->getHeaders()->toArray();

        $controllerHeaders = $controllerResponse->getHeaders();
        $controllerHeaders->clearHeaders()->addHeaders($fileManagerHeaders);

        return $controllerResponse;
    }
}
