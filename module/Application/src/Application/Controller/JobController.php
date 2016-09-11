<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class JobController extends ApplicationController
{
    public function indexAction()
    {
        $menu = $this->menu;

        if ($menu->rows->jobs->active == 1) {

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
        $page = $this->params()->fromQuery('page');

        $category = $this->params()->fromRoute('category');

        $jobCategories = $this->api->jobCategory->getData($this->lang);

        $uriCategories = array_column($jobCategories['locale'][$this->lang], 'relatedTableId', 'urlKey');

        if (isset($uriCategories[$category]) and $jobCategories['rows'][$uriCategories[$category]]['active'] == '1') {

            $jobs = $this->api->job->getData($this->lang, $category, $page);

            $viewModel = new ViewModel(array(
                'menu'              => $this->menu,
                'lang'              => $this->lang,
                'jobs'              => $jobs,
                'currentCategory'   => $jobCategories['locale'][$this->lang][$uriCategories[$category]],
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

                $viewModel = new ViewModel(array(
                    'menu'              => $this->menu,
                    'lang'              => $this->lang,
                    'job'               => $job->current(),
                    'currentCategory'   => $jobCategories['locale'][$this->lang][$uriCategories[$category]],
                    'jobCategories'     => $jobCategories
                ));

                return $viewModel;
            }
        }

        $this->getResponse()->setStatusCode(404);
    }
}