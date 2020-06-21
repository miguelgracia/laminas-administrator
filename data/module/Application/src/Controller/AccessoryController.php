<?php

namespace Application\Controller;

use Api\Service\AccessoryService;
use Application\Service\GalleryRenderService;
use Zend\View\Model\JsonModel;

class AccessoryController extends ApplicationController
{
    public function indexAction()
    {
        $page = (int) $this->params()->fromQuery('page');

        $galleryRenderService = $this->serviceManager->build(GalleryRenderService::class, [
            'ViewRenderer' => $this->serviceManager->get('ViewRenderer')
        ]);

        $accessoryService = $this->serviceManager->get(AccessoryService::class);

        return new JsonModel([
            'content' => $galleryRenderService->render([
                'gallery' => $accessoryService->getAccessories($this->lang, false, $page),
                'galleryType' => 'accessories-gallery'
            ]),
            'nextPage' => $this->url()->fromRoute(
                'locale/accessories',
                ['locale' => $this->lang, 'type' => 'accessories'],
                ['query' => ['page' => $page + 1]]
            ),
        ]);
    }
}
