<?php

namespace MediaDispatcher\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class DispatcherController extends AbstractActionController
{
    protected $imageService;

    public function dispatchAction()
    {
        $path = $this->params()->fromQuery('path');
        $width = $this->params()->fromQuery('width');
        $height = $this->params()->fromQuery('height');
        $clearCache = (bool) $this->params()->fromQuery('cache');
        $color = $this->params()->fromQuery('background', '#000000');

        $this->imageService->setImageBackground($color);

        try {
            $this->imageService->setImagePath($path);
            $image = $this->imageService->createImage($width, $height, $clearCache);
        } catch (\Exception $ex) {
            $this->imageService->setImagePath('/img/white-logo.png', DIRECTORY_SEPARATOR);
            $image = $this->imageService->createImage($width, $height, $clearCache);
        }

        $response = $this->getResponse();

        $headers = $response->getHeaders();

        $headers->addHeaderLine('Content-Type', $image->mime());
        //$headers->addHeaderLine('Content-Length',$image->filesize());
        $response->setContent($image->encode());

        return $response;
    }

    public function onDispatch(MvcEvent $e)
    {
        $this->imageService = $e->getApplication()->getServiceManager()->get('dinamicImage');
        return parent::onDispatch($e);
    }
}
