<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class JobController extends ApplicationController
{
    public function indexAction()
    {
        $menu = $this->menu;

        if (isset($menu->rows->jobs) and $menu->rows->jobs->active == 1) {

            $menuLang = $menu->locale->{$this->lang};

            $this->headTitleHelper->append($menuLang[$menu->rows->jobs->id]->name);


            $page = $this->params()->fromQuery('page');

            $jobs = $this->api->job->getData($this->lang, false, $page);
            $jobCategories = $this->api->jobCategory->getData($this->lang);

            return new ViewModel(array(
                'menu'            => $this->menu,
                'lang'            => $this->lang,
                'jobs'            => $jobs,
                'jobCategories'   => $jobCategories,
                'routePagination' => $this->lang .'/jobs',
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

        $jobCategories = $this->api->jobCategory->getData($this->lang);

        $uriCategories = array_column($jobCategories['locale'][$this->lang], 'relatedTableId', 'urlKey');

        if (isset($uriCategories[$category]) and $jobCategories['rows'][$uriCategories[$category]]['active'] == '1') {

            $menuLang = $menu->locale->{$this->lang};

            $currentCategory = $jobCategories['locale'][$this->lang][$uriCategories[$category]];

            $this->headTitleHelper->append($menuLang[$menu->rows->jobs->id]->name);
            $this->headTitleHelper->append($currentCategory['title']);

            $jobs = $this->api->job->getData($this->lang, $category, $page);

            $viewModel = new ViewModel(array(
                'menu'              => $this->menu,
                'lang'              => $this->lang,
                'jobs'              => $jobs,
                'currentCategory'   => $currentCategory,
                'jobCategories'     => $jobCategories,
                'routePagination'   => $this->lang .'/jobs/category',
                'routeParams'       => array(
                    'category' => $category
                )
            ));

            $viewModel->setTemplate('application/job/index');

            return $viewModel;
        }

        $this->getResponse()->setStatusCode(404);
    }

    public function detailAction()
    {
        $category = $this->params()->fromRoute('category');
        $jobUri = $this->params()->fromRoute('detail');

        $jobCategories = $this->api->jobCategory->getData($this->lang);

        $uriCategories = array_column($jobCategories['locale'][$this->lang], 'relatedTableId', 'urlKey');

        if (isset($uriCategories[$category]) and $jobCategories['rows'][$uriCategories[$category]]['active'] == '1') {

            $job = $this->api->job->getDetail($this->lang, $jobUri);

            if ($job->count()) {

                $job = $job->current();

                $menuLang = $this->menu->locale->{$this->lang};

                $currentCategory = $jobCategories['locale'][$this->lang][$uriCategories[$category]];

                $this->headTitleHelper->append($menuLang[$this->menu->rows->jobs->id]->name);
                $this->headTitleHelper->append($currentCategory['title']);
                $this->headTitleHelper->append($job->title);

                $viewModel = new ViewModel(array(
                    'menu'              => $this->menu,
                    'lang'              => $this->lang,
                    'job'               => $job,
                    'currentCategory'   => $currentCategory,
                    'jobCategories'     => $jobCategories
                ));

                return $viewModel;
            }
        }

        $this->getResponse()->setStatusCode(404);
    }
}