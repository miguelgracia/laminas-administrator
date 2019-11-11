<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Paginator\Paginator;

class JobCategory extends AbstractHelper
{
    public function getContentWrapper()
    {
        return "<li><a href='%s'>%s</a></li>";
    }

    public function render($lang, $jobCategories)
    {
        $url = $this->getView()->getHelperPluginManager()->get('Url');

        $html = '';

        foreach ($jobCategories['rows'] as $index => $jobCategory) {

            if ($jobCategory['active'] == '1') {
                $html .= sprintf(
                    $this->getContentWrapper(),
                    $url('locale/jobs/category',array(
                        'locale' => $lang,
                        'category' => $jobCategories['locale'][$lang][$jobCategory['id']]['urlKey']
                    )),
                    $jobCategories['locale'][$lang][$jobCategory['id']]['title']
                );
            }
        }

        echo $html;
    }
}