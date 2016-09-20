<?php

namespace MediaDispatcher\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class DispatcherController extends AbstractActionController
{
    public function dispatchAction()
    {
        $imageService = $this->serviceLocator->get('OgImage');

        $path = ($this->params()->fromQuery('path'));

        try {
            $imageService->setImagePath($path);
            $image = $imageService->createImage(400,600);
        } catch (\Exception $ex) {
            $imageService->setImagePath('/img/white-logo.png',DIRECTORY_SEPARATOR);
            $image = $imageService->createImage(400,600);
        }

        $response = $this->getResponse();

        $headers = $response->getHeaders();

        $headers->addHeaderLine('Content-Type',$image->mime());

        $response->setContent($image->encode());

        return $response;
    }
}