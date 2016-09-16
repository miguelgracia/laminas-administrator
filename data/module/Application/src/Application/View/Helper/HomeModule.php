<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class HomeModule extends AbstractHelper
{
    function __invoke()
    {
        return $this;
    }

    public function getListElement()
    {
        return "<div class='row home-module'>%s%s</div>";
    }

    public function getImageWrapper()
    {
        return "<div class='col-md-6 col-sm-6 col-xs-6 %s'>
                    <div class='owl-carousel-home'>
                        <div class='item'>
                            <img class='img-responsive' src='%s' />
                        </div>
                    </div>
                </div>";
    }

    public function getContentWrapper()
    {
        return "<div class='col-md-6 col-sm-6 col-xs-6 %s'>
                    <h2>%s</h2>
                    %s
                    <a href='%s' target='%s' class='btn btn-default'>%s</a>
                </div>";
    }

    public function render($homeModules)
    {
        $html = '';

        foreach ($homeModules as $index => $homeModule) {

            $isEven = $index % 2 == 0;

            $imageWrapper = sprintf(
                $this->getImageWrapper(),
                ($isEven ? "col-md-push-6 col-sm-push-6" : ""),
                '/media/'.$homeModule->locale->imageUrl
            );

            $contentWrapper = sprintf(
                $this->getContentWrapper(),
                ($isEven ? "col-md-pull-6 col-sm-pull-6" : ""),
                $homeModule->locale->title,
                $homeModule->locale->content,
                $homeModule->locale->languageCode.$homeModule->locale->linkUrl,
                $homeModule->locale->targetLink,
                $homeModule->locale->linkText
            );

            $html .= sprintf(
                $this->getListElement(),
                $imageWrapper,
                $contentWrapper
            );
        }

        echo $html;
    }
}