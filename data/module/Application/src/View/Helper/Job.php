<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Paginator\Paginator;

class Job extends AbstractHelper
{
    public function getContentWrapper()
    {
        return
            "<div class='col-md-12 list-item job'>
                <div class='row'>
                    <div class='col-md-7'><img class='img-responsive' src='%s' /></div>
                    <div class='col-md-5'>
                        <h3><a href='%s'>%s</a></h3>
                        %s
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-9'>
                        <span>
                            <i class='fa fa-tag'></i>
                            <a href='%s'>%s</a>
                        </span>
                    </div>
                    <div class='col-md-3'>
                        <a href='%s' class='btn btn-default pull-right'>%s</a>
                    </div>
                </div>
            </div>";
    }

    private function getSeparator()
    {
        return "<div class='clearfix visible-sm-block'></div>";
    }

    private function getFeaturedWrapper()
    {
        return "<div class='col-md-4 col-sm-6 col-xxs-12'>
                <a href='%s' class='abstpl-project-item image-popup to-animate'>
                    <img src='%s' alt='Image' class='img-responsive'>
                    <div class='abstpl-text'>
                        <h2>%s</h2>
                        <span>%s</span>
                    </div>
                </a>
            </div>";
    }

    public function renderFeatured($lang, $jobs)
    {
        $html = '';

        foreach ($jobs as $index => $job) {
            $job->imageUrl = json_decode($job->imageUrl);

            $imageUrl = (is_array($job->imageUrl) ? $job->imageUrl[0] : $job->imageUrl);

            $html .= sprintf(
                $this->getFeaturedWrapper(),
                $imageUrl,
                $imageUrl,
                $job->title,
                $job->categoryTitle
            );

            if (($index + 1) % 2 === 0) {
                $html .= $this->getSeparator();
            }
        }

        echo $html;
    }

    public function render($lang, $jobs)
    {
        $url = $this->getView()->getHelperPluginManager()->get('Url');

        $html = '';

        if ($jobs instanceof Paginator) {
            foreach ($jobs as $index => $job) {
                $job->imageUrl = json_decode($job->imageUrl);
                $linkUrl = $url('locale/jobs/category/detail', [
                    'locale' => $lang,
                    'category' => $job->categoryUrlKey,
                    'detail' => $job->urlKey
                ]);

                $html .= sprintf(
                    $this->getContentWrapper(),
                    (is_array($job->imageUrl) ? $job->imageUrl[0] : $job->imageUrl),
                    $linkUrl,
                    $job->title,
                    $job->content,
                    $url('locale/jobs/category', [
                        'locale' => $lang,
                        'category' => $job->categoryUrlKey
                    ]),
                    $job->categoryTitle,
                    $linkUrl,
                    $this->translator->translate('Show work', 'frontend')
                );
            }
        }

        echo $html;
    }
}
