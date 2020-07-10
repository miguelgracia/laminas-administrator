<?php

namespace Application\Controller;

use Api\Service\AccessoryCategoryService;
use Api\Service\AccessoryService;
use Application\Service\GalleryRenderService;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class AccessoryController extends ApplicationController
{
    public function homeAction()
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

    public function indexAction()
    {
        $this->layout()->setTemplate('layout/accessories');

        $menu = $this->menu;

        $menuLang = $menu->locale->{$this->lang};

        $menuLangAccessory = $menuLang[$menu->rows->accessories->id];

        $this->headTitleHelper->append($menuLangAccessory->name);

        $ogFacebook = $this->openGraph->facebook();
        $ogFacebook->title = $this->headTitleHelper->renderTitle();
        $ogFacebook->description = $menuLangAccessory->metaDescription;

        $this->layout()->setVariable('og',$ogFacebook);

        $page = $this->params()->fromQuery('page');

        $accessories = $this->serviceManager->get(AccessoryService::class)->getData($this->lang, false, $page);
        $accessoriesCategories = $this->serviceManager->get(AccessoryCategoryService::class)->getData($this->lang);

        return new ViewModel([
            'menu' => $this->menu,
            'lang' => $this->lang,
            'accessories' => $accessories,
            'accessoriesCategories' => $accessoriesCategories,
            'routePagination' => 'locale/accessories',
            'routeParams' => []
        ]);
    }

    public function categoryAction()
    {
        $this->layout()->setTemplate('layout/accessories');

        $menu = $this->menu;

        $page = $this->params()->fromQuery('page');

        $category = $this->params()->fromRoute('category');

        $accessoriesCategories = $this->serviceManager->get(AccessoryCategoryService::class)->getData($this->lang);

        $uriCategories = array_column($accessoriesCategories['locale'][$this->lang], 'relatedTableId', 'urlKey');

        if ($accessoriesCategories['rows'][$uriCategories[$category]]['active'] == '0') {
            return $this->getResponse()->setStatusCode(404);
        }

        $menuLang = $menu->locale->{$this->lang};

        $currentCategory = $accessoriesCategories['locale'][$this->lang][$uriCategories[$category]];

        $menuLangAccessory = $menuLang[$menu->rows->accessories->id];

        $this->headTitleHelper->append($menuLangAccessory->name);
        $this->headTitleHelper->append($currentCategory['title']);

        $ogFacebook = $this->openGraph->facebook();
        $ogFacebook->title = $this->headTitleHelper->renderTitle();
        $ogFacebook->description = $menuLangAccessory->metaDescription;

        $this->layout()->setVariable('og', $ogFacebook);
        $this->layout()->setVariable('currentCategory', $currentCategory);

        $accessories = $this->serviceManager->get(AccessoryService::class)->getData($this->lang, $category, $page);

        $viewModel = new ViewModel([
            'menu' => $this->menu,
            'lang' => $this->lang,
            'accessories' => $accessories,
            'currentCategory' => $currentCategory,
            'accessoriesCategories' => $accessoriesCategories,
            'routePagination' => 'locale/accessories/category',
            'routeParams' => [
                'category' => $category
            ]
        ]);

        $viewModel->setTemplate('application/accessory/index');

        return $viewModel;
    }
}
