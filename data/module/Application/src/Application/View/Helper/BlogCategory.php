<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Paginator\Paginator;

class BlogCategory extends AbstractHelper
{
    function __invoke()
    {
        return $this;
    }

    public function getContentWrapper()
    {
        return "<li><a href='%s'>%s</a></li>";
    }

    public function render($lang, $blogCategories)
    {
        $url = $this->getView()->getHelperPluginManager()->get('Url');

        $html = '';

        foreach ($blogCategories['rows'] as $index => $blogCategory) {

            if ($blogCategory['active'] == '1') {
                $html .= sprintf(
                    $this->getContentWrapper(),
                    $url($lang.'/blog/category',array(
                        'category' => $blogCategories['locale'][$lang][$blogCategory['id']]['urlKey']
                    )),
                    $blogCategories['locale'][$lang][$blogCategory['id']]['title']
                );
            }
        }

        echo $html;
    }
}