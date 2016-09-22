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

        try {
            $imageService->setImagePath($path);
            $image = $imageService->createImage($width,$height);
        } catch (\Exception $ex) {
            $imageService->setImagePath('/img/white-logo.png',DIRECTORY_SEPARATOR);
            $image = $imageService->createImage($width,$height);
        }

        $response = $this->getResponse();

        $headers = $response->getHeaders();

        $headers->addHeaderLine('Content-Type',$image->mime());
        //$headers->addHeaderLine('Content-Length',$image->filesize());
        $response->setContent($image->encode());

        return $response;
    }
}