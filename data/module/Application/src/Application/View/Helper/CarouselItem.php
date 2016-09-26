<?php

namespace Application\View\Helper;


use Zend\Code\Scanner\FileScanner;
use Zend\Filter\BaseName;
use Zend\Filter\Dir;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\MimeType;
use Zend\View\Helper\AbstractHelper;

class CarouselItem extends AbstractHelper
{
    protected $headTitle;

    public function __invoke()
    {
        return $this;
    }

    protected function getItemWrapper()
    {
        return "<div class='item'>%s</div>";
    }

    protected function getHtmlElementByPath($path)
    {
        $baseNameFilter = new BaseName();
        $dirFilter = new Dir();

        $helperPluginManager = $this->getView()->getHelperPluginManager();

        $title = $helperPluginManager->get('headTitle');

        $elementPath = $_SERVER['DOCUMENT_ROOT'] . $path;

        $isImage = new IsImage();

        $mimeVideo = new MimeType(array('video','application'));

        if ($isImage->isValid($elementPath)) {
            return "<img class='img-responsive' src='$path' alt='".$title->renderTitle()."'>";
        } elseif($mimeVideo->isValid($elementPath)) {
            $dirName = $dirFilter->filter($path);
            $baseName = $baseNameFilter->filter($path);
            $videoPoster = $dirName .'/video-poster-'.md5($baseName).'.jpg';
            return "<video poster='$videoPoster' width='100%' src='$path'></video>";
        }

        return false;
    }

    public function render($elementsPath = array())
    {
        $html = array();

        foreach ($elementsPath as $elementPath) {
            $element = $this->getHtmlElementByPath($elementPath);
            if ($element) {
                $html[] = sprintf($this->getItemWrapper(), $element);
            }
        }

        return implode("\n",$html);
    }
}