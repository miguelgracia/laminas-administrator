<?php

namespace Application\View\Helper;

use Administrator\Validator\Youtube;
use Laminas\Code\Scanner\FileScanner;
use Laminas\Filter\BaseName;
use Laminas\Filter\Dir;
use Laminas\Validator\File\IsImage;
use Laminas\Validator\File\MimeType;
use Laminas\View\Helper\AbstractHelper;

class CarouselItem extends AbstractHelper
{
    protected $headTitle;

    protected $youTubeValidator;

    public function __invoke()
    {
        $this->youTubeValidator = new Youtube();

        return $this;
    }

    protected function getItemWrapper($elementPath)
    {
        $class = 'brick';
        if ($this->youTubeValidator->isValid($elementPath)) {
            $class .= ' video iframe';
        }
        return "<div class='$class'>%s</div>";
    }

    protected function getHtmlElementByPath($path)
    {
        $baseNameFilter = new BaseName();
        $dirFilter = new Dir();

        $helperPluginManager = $this->getView()->getHelperPluginManager();

        if (!$this->youTubeValidator->isValid($path)) {
            $title = $helperPluginManager->get('headTitle');

            $elementPath = $_SERVER['DOCUMENT_ROOT'] . $path;

            $isImage = new IsImage();
            $mimeVideo = new MimeType(['video', 'application']);

            if ($isImage->isValid($elementPath)) {
                return "<i class='fa fa-search-plus'></i><img class='img-responsive' src='$path' alt='" . $title->renderTitle() . "'>";
            } elseif ($mimeVideo->isValid($elementPath)) {
                $dirName = $dirFilter->filter($path);
                $baseName = $baseNameFilter->filter($path);
                $videoPoster = $dirName . '/video-poster-' . md5($baseName) . '.jpg';
                return "<video poster='$videoPoster' width='100%' controls src='$path'></video>";
            }

            return false;
        } else {
            return "<iframe src='$path?rel=0&amp;controls=1&amp;showinfo=0' frameborder='0' allowfullscreen></iframe>";
        }
    }

    public function render($elementsPath = [])
    {
        $html = [];

        foreach ($elementsPath as $elementPath) {
            $element = $this->getHtmlElementByPath($elementPath);
            if ($element) {
                $html[] = sprintf($this->getItemWrapper($elementPath), $element);
            }
        }
        return implode("\n", $html);
    }
}
