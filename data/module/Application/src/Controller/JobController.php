<?php

namespace Application\Controller;

use Api\Service\JobCategoryService;
use Api\Service\JobService;
use Application\Service\GalleryRenderService;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class JobController extends ApplicationController
{
    public function homeAction()
    {
        $page = (int) $this->params()->fromQuery('page');

        $galleryRenderService = $this->serviceManager->build(GalleryRenderService::class, [
            'ViewRenderer' => $this->serviceManager->get('ViewRenderer')
        ]);

        $jobService = $this->serviceManager->get(JobService::class);

        return new JsonModel([
            'content' => $galleryRenderService->render([
                'gallery' => $jobService->getJobs($this->lang, false, $page),
                'galleryType' => 'jobs-gallery'
            ]),
            'nextPage' => $this->url()->fromRoute(
                'locale/jobs',
                ['locale' => $this->lang, 'type' => 'jobs'],
                ['query' => ['page' => $page + 1]]
            ),
        ]);
    }

    public function categoryAction()
    {
        $menu = $this->menu;

        $page = $this->params()->fromQuery('page');

        $category = $this->params()->fromRoute('category');

        $jobCategories = $this->serviceManager->get(JobCategoryService::class)->getData($this->lang);

        $uriCategories = array_column($jobCategories['locale'][$this->lang], 'relatedTableId', 'urlKey');

        if (isset($uriCategories[$category]) and $jobCategories['rows'][$uriCategories[$category]]['active'] == '1') {
            $menuLang = $menu->locale->{$this->lang};

            $currentCategory = $jobCategories['locale'][$this->lang][$uriCategories[$category]];

            $menuLangJob = $menuLang[$menu->rows->jobs->id];

            $this->headTitleHelper->append($menuLangJob->name);
            $this->headTitleHelper->append($currentCategory['title']);

            $ogFacebook = $this->openGraph->facebook();
            $ogFacebook->title = $this->headTitleHelper->renderTitle();
            $ogFacebook->description = $menuLangJob->metaDescription;

            $this->layout()->setVariable('og', $ogFacebook);

            $jobs = $this->serviceManager->get(JobService::class)->getData($this->lang, $category, $page);

            $viewModel = new ViewModel([
                'menu' => $this->menu,
                'lang' => $this->lang,
                'jobs' => $jobs,
                'currentCategory' => $currentCategory,
                'jobCategories' => $jobCategories,
                'routePagination' => $this->lang . '/jobs/category',
                'routeParams' => [
                    'category' => $category
                ]
            ]);

            $viewModel->setTemplate('application/job/index');

            return $viewModel;
        }

        $this->getResponse()->setStatusCode(404);
    }

    public function detailAction()
    {
        $category = $this->params()->fromRoute('category');
        $jobUri = $this->params()->fromRoute('detail');

        $jobCategories = $this->serviceManager->get(JobCategoryService::class)->getData($this->lang);

        $uriCategories = array_column($jobCategories['locale'][$this->lang], 'relatedTableId', 'urlKey');

        if (isset($uriCategories[$category]) and $jobCategories['rows'][$uriCategories[$category]]['active'] == '1') {
            $job = $this->serviceManager->get(JobService::class)->getDetail($this->lang, $jobUri);

            if ($job->count()) {
                $job = $job->current();

                $menuLang = $this->menu->locale->{$this->lang};

                $currentCategory = $jobCategories['locale'][$this->lang][$uriCategories[$category]];

                $menuLangJob = $menuLang[$this->menu->rows->jobs->id];

                $this->headTitleHelper->append($menuLangJob->name);
                $this->headTitleHelper->append($currentCategory['title']);
                $this->headTitleHelper->append($job->title);

                $ogFacebook = $this->openGraph->facebook();
                $ogFacebook->title = $this->headTitleHelper->renderTitle();
                $ogFacebook->description = $job->metaDescription;

                $ogFacebook->image = json_decode($job->getImageUrl());

                $this->layout()->setVariable('og', $ogFacebook);

                $viewModel = new ViewModel([
                    'menu' => $this->menu,
                    'lang' => $this->lang,
                    'job' => $job,
                    'currentCategory' => $currentCategory,
                    'jobCategories' => $jobCategories
                ]);

                return $viewModel;
            }
        }

        $this->getResponse()->setStatusCode(404);
    }
}
