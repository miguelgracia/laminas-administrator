<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Paginator\Paginator;

class Job extends AbstractHelper
{
    function __invoke()
    {
        return $this;
    }

    public function getContentWrapper()
    {
        return "<div class='col-md-12 list-item job'>
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

    public function render($lang, $jobs)
    {
        $url = $this->getView()->getHelperPluginManager()->get('Url');

        $html = '';

        if ($jobs instanceof Paginator) {

            foreach ($jobs as $index => $job) {

                $job->imageUrl = json_decode($job->imageUrl);
                $linkUrl = $url($lang . '/jobs/category/detail', array(
                    'category' => $job->categoryUrlKey,
                    'detail' => $job->urlKey
                ));

                $html .= sprintf(
                    $this->getContentWrapper(),
                    (is_array($job->imageUrl) ? $job->imageUrl[0] : $job->imageUrl),
                    $linkUrl,
                    $job->title,
                    $job->content,
                    $url($lang.'/jobs/category',array(
                        'category' => $job->categoryUrlKey
                    )),
                    $job->categoryTitle,
                    $linkUrl,
                    $this->translator->translate('Show work','frontend')
                );
            }
        }

        echo $html;
    }
}