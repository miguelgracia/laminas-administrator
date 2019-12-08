<?php

namespace Application\View\Helper;

use Administrator\Validator\Youtube;
use Zend\Form\View\Helper\AbstractHelper;

class HomeModule extends AbstractHelper
{
    public function getListElement()
    {
        return "<div class='row home-module'>%s%s</div>";
    }

    public function getElementItem()
    {
        return "<div class='item %s' data-src='%s'>
                    %s
                </div>";
    }

    public function getImageWrapper()
    {
        return "<div class='col-md-6 col-sm-6 col-xs-6 %s'>
                    <div class='owl-carousel owl-carousel-home'>
                        %s
                    </div>
                </div>";
    }

    public function getContentWrapper()
    {
        return "<div class='col-md-6 col-sm-6 col-xs-6 %s'>
                    <h2><a href='%s'>%s</a></h2>
                    %s
                    <a href='%s' target='%s' class='btn btn-default'>%s</a>
                </div>";
    }

    public function render($homeModules)
    {
        $html = '';

        $youtubeValidator = new Youtube();

        foreach ($homeModules as $index => $homeModule) {
            $isEven = $index % 2 == 0;

            $urlElements = json_decode($homeModule->locale->imageUrl);
            $elements = [];

            foreach ($urlElements as $indexElement => $urlElement) {
                $class = '';

                $src = $urlElement;

                if ($youtubeValidator->isValid($urlElement)) {
                    $videoId = preg_replace(
                        "/(.+)\/(.{11})$/",
                        '$2',
                        $urlElement
                    );

                    //$urlElement = sprintf("http://img.youtube.com/vi/%s/mqdefault.jpg", $videoId);
                    $urlElement = sprintf("<iframe id='player_$indexElement' type='text/html' src='http://www.youtube.com/embed/%s?enablejsapi=1&rel=0&controls=1&showinfo=0'
  frameborder='0'></iframe><div class='youtube-play' style='display: none;'></div> ", $videoId);
                    $src = $videoId;
                    $class .= 'video iframe';
                } else {
                    $urlElement = sprintf("<img class='img-responsive' src='%s' />", $urlElement);
                }
                $elements[] = sprintf(
                    $this->getElementItem(),
                    $class,
                    $src,
                    $urlElement
                );
            }

            $imageWrapper = sprintf(
                $this->getImageWrapper(),
                ($isEven ? 'col-md-push-6 col-sm-push-6 col-xs-push-6' : ''),
                implode("\n", $elements)
            );

            $linkUrl = $homeModule->locale->languageCode . $homeModule->locale->linkUrl;
            $contentWrapper = sprintf(
                $this->getContentWrapper(),
                ($isEven ? 'col-md-pull-6 col-sm-pull-6 col-xs-pull-6' : ''),
                $linkUrl,
                $homeModule->locale->title,
                $homeModule->locale->content,
                $linkUrl,
                $homeModule->locale->targetLink,
                $homeModule->locale->linkText
            );

            $sprintfParams = [
                $this->getListElement(),
                $imageWrapper,
                $contentWrapper
            ];

            $html .= call_user_func_array('sprintf', $sprintfParams);
        }

        echo $html;
    }
}
