<?php

namespace MediaDispatcher\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class DispatcherController extends AbstractActionController
{
    public function dispatchAction()
    {
        $imageService = $this->serviceLocator->get('dinamicImage');

        $path = $this->params()->fromQuery('path');
        $width = $this->params()->fromQuery('width');
        $height = $this->params()->fromQuery('height');
        $clearCache = (bool) $this->params()->fromQuery('cache');
        $color = (bool) $this->params()->fromQuery('background');
        $color = '#000000';

        $imageService->setImageBackground($color);

        try {
            $imageService->setImagePath($path);
            $image = $imageService->createImage($width,$height, $clearCache);
        } catch (\Exception $ex) {
            $imageService->setImagePath('/img/white-logo.png',DIRECTORY_SEPARATOR);
            $image = $imageService->createImage($width,$height, $clearCache);
        }

        $response = $this->getResponse();

        $headers = $response->getHeaders();

        $headers->addHeaderLine('Content-Type',$image->mime());
        //$headers->addHeaderLine('Content-Length',$image->filesize());
        $response->setContent($image->encode());

        return $response;
    }
}