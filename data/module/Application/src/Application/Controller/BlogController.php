<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class BlogController extends ApplicationController
{
    public function indexAction()
    {
        $menu = $this->menu;

        if (isset($menu->rows->blog) and $menu->rows->blog->active == 1) {

            $menuLang = $menu->locale->{$this->lang};

            $this->headTitleHelper->append($menuLang[$menu->rows->blog->id]->name);

            $page = $this->params()->fromQuery('page');

            $blogs = $this->api->blog->getData($this->lang, false, $page);
            $blogCategories = $this->api->blogCategory->getData($this->lang);

            return new ViewModel(array(
                'menu'            => $this->menu,
                'lang'            => $this->lang,
                'blogs'            => $blogs,
                'blogCategories'   => $blogCategories,
                'routePagination' => $this->lang .'/blog',
                'routeParams'     => array()
            ));
        }

        $this->getResponse()->setStatusCode(404);
    }

    public function categoryAction()
    {
        $menu = $this->menu;

        $page = $this->params()->fromQuery('page');

        $category = $this->params()->fromRoute('category');

        $blogCategories = $this->api->blogCategory->getData($this->lang);

        $uriCategories = array_column($blogCategories['locale'][$this->lang], 'relatedTableId', 'urlKey');

        if (isset($uriCategories[$category]) and $blogCategories['rows'][$uriCategories[$category]]['active'] == '1') {

            $menuLang = $menu->locale->{$this->lang};

            $currentCategory = $blogCategories['locale'][$this->lang][$uriCategories[$category]];

            $this->headTitleHelper->append($menuLang[$menu->rows->blog->id]->name);
            $this->headTitleHelper->append($currentCategory['title']);

            $blogs = $this->api->blog->getData($this->lang, $category, $page);

            $viewModel = new ViewModel(array(
                'menu'              => $this->menu,
                'lang'              => $this->lang,
                'blogs'              => $blogs,
                'currentCategory'   => $currentCategory,
                'blogCategories'     => $blogCategories,
                'routePagination'   => $this->lang .'/blog/category',
                'routeParams'       => array(
                    'category' => $category
                )
            ));

            $viewModel->setTemplate('application/blog/index');

            return $viewModel;
        }

        $this->getResponse()->setStatusCode(404);
    }

    public function detailAction()
    {
        $category = $this->params()->fromRoute('category');
        $blogUri = $this->params()->fromRoute('detail');

        $blogCategories = $this->api->blogCategory->getData($this->lang);

        $uriCategories = array_column($blogCategories['locale'][$this->lang], 'relatedTableId', 'urlKey');

        if (isset($uriCategories[$category]) and $blogCategories['rows'][$uriCategories[$category]]['active'] == '1') {

            $blog = $this->api->blog->getDetail($this->lang, $blogUri);

            if ($blog->count()) {

                $blog = $blog->current();

                $menuLang = $this->menu->locale->{$this->lang};

                $currentCategory = $blogCategories['locale'][$this->lang][$uriCategories[$category]];

                $this->headTitleHelper->append($menuLang[$this->menu->rows->blog->id]->name);
                $this->headTitleHelper->append($currentCategory['title']);
                $this->headTitleHelper->append($blog->title);

                $viewModel = new ViewModel(array(
                    'menu'              => $this->menu,
                    'lang'              => $this->lang,
                    'blog'               => $blog,
                    'currentCategory'   => $currentCategory,
                    'blogCategories'     => $blogCategories
                ));

                return $viewModel;
            }
        }

        $this->getResponse()->setStatusCode(404);
    }
}
