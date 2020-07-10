<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Paginator\Paginator;

class JobCategory extends AbstractHelper
{
    public function getContentWrapper()
    {
        return "<li class='nav-item %s'><a class='nav-link' href='%s'>%s</a></li>";
    }

    public function render($lang, $jobCategories, $currentCategory = null)
    {
        $url = $this->getView()->getHelperPluginManager()->get('Url');

        $html = '';

        foreach ($jobCategories['rows'] as $index => $jobCategory) {
            if ($jobCategory['active'] == '1') {
                $html .= sprintf(
                    $this->getContentWrapper(),
                    (is_array($currentCategory) and
                    $currentCategory['relatedTableId'] === $jobCategory['id']) ? 'active' : '',
                    $url('locale/accessories/category', [
                        'locale' => $lang,
                        'category' => $jobCategories['locale'][$lang][$jobCategory['id']]['urlKey']
                    ]),
                    $jobCategories['locale'][$lang][$jobCategory['id']]['title']
                );
            }
        }

        echo $html;
    }
}
