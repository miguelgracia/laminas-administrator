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

    private function getVideoTemplate()
    {
        return "<div class='video-wrapper'>
                        <video class='video' width='71%%' poster='%s'>
                                <source src='%s' type='video/mp4'/>
                        </video>
                        <div class='video-filter'></div>
                        <div class='video-controls'>
                            <i class='fa fa-play'></i>
                            <i class='fa fa-pause hide'></i>
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
        $baseNameFilter = new BaseName();
        $dirFilter = new Dir();
        $html = '';

        $dinamicImage = $this->getView()->getHelperPluginManager()->get('dinamicImageHelper');

        foreach ($megabanners as $megabanner) {

            if ($megabanner->isVideo) {
                $element = $this->getVideoTemplate();
                $elementUrl = $megabanner->elementUrl;
                $dirName = $dirFilter->filter($elementUrl);
                $baseName = $baseNameFilter->filter($elementUrl);
                $videoPoster = $dirName .'/video-poster-'.md5($baseName).'.jpg';
                $sprintfParams = array(
                    $element,
                    $videoPoster,
                    $elementUrl
                );
            } else {
                $element =  $this->getImageTemplate();
                $elementUrl = $dinamicImage($megabanner->elementUrl)->makeUrl(null,550,'megabanner');
                $sprintfParams = array(
                    $element,
                    $elementUrl
                );
            }

            $elementHtml = call_user_func_array('sprintf',$sprintfParams);

            $html .= sprintf($this->getListTemplate(),$elementHtml);
        }

        echo $html;
    }
}