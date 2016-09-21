<?php

namespace Application\View\Helper;


use Zend\Form\View\Helper\AbstractHelper;

class Megabanner extends AbstractHelper
{
    function __invoke()
    {
        return $this;
    }

    private function getVideoTemplate()
    {
        return "<div class='video-wrapper'>
                        <video class='video' width='71%%'>
                            <source src='%s' type='video/mp4'/>
                        </video>
                        <div class='video-filter'>
                            <div class='video-controls'>
                                <i class='fa fa-play'></i>
                                <i class='fa fa-pause hide'></i>
                            </div>
                        </div>
                    </div>";
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

            if ($megabanner->isVideo) {
                $element = $this->getVideoTemplate();
                $elementUrl = $megabanner->locale->elementUrl;
            } else {
                $element =  $this->getImageTemplate();
                $elementUrl = $dinamicImage($megabanner->locale->elementUrl)->makeUrl(null,550,'megabanner');
            }


            $elementHtml = sprintf($element, $elementUrl);

            $html .= sprintf($this->getListTemplate(),$elementHtml);
        }

        echo $html;
    }
}