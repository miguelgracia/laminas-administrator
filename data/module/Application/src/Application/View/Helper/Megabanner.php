<?php

namespace Application\View\Helper;


use Zend\Filter\BaseName;
use Zend\Filter\Dir;
use Zend\Form\View\Helper\AbstractHelper;

class Megabanner extends AbstractHelper
{
    function __invoke()
    {
        return $this;
    }

    private function getImageTemplate()
    {
        return "<img src='%s' />\n
                <div class='video-filter'></div>\n";
    }

    private function getListTemplate()
    {
        return "<li>%s</li>";
    }

    function render($megabanners)
    {
        $html = '';

        $dinamicImage = $this->getView()->getHelperPluginManager()->get('dinamicImageHelper');

        foreach ($megabanners as $megabanner) {

            $element =  $this->getImageTemplate();
            $elementUrl = $dinamicImage($megabanner->elementUrl)->makeUrl(null,550,'megabanner');
            $sprintfParams = array(
                $element,
                $elementUrl
            );

            $elementHtml = call_user_func_array('sprintf',$sprintfParams);

            $html .= sprintf($this->getListTemplate(),$elementHtml);
        }

        echo $html;
    }
}