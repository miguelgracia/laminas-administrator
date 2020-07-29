<?php

namespace Application\View\Helper;

use Administrator\Validator\Youtube;
use Laminas\Code\Scanner\FileScanner;
use Laminas\Filter\BaseName;
use Laminas\Filter\Dir;
use Laminas\Validator\File\IsImage;
use Laminas\Validator\File\MimeType;
use Laminas\View\Helper\AbstractHelper;

class CertificationItem extends AbstractHelper
{
    public function __invoke($certifications = [])
    {
        $html = '';

        foreach ($certifications as $certification) {
            $html .= $this->getTemplate($certification->fileUrl !== '', $certification);
        }

        return sprintf('<div id="abstpl-certifications">%s</div>', $html);
    }

    private function getTemplate($withFile, $certification)
    {
        $linkTemplate = '<a target="_blank" class="certification-icon-info"
                   href="%s"
                   title="Ver certificado">
                    %s
                    <span class="icon-file-pdf-o"></span>
                </a>';

        $img = sprintf('<img height="100" src="%s" alt="Ver certificado">', $certification->logo);

        $html = '<div class="subtext to-animate">
                    <div class="text-center to-animate">
                    %s
                    </div>
                </div>';

        if ($withFile) {
            $link = sprintf($linkTemplate, $certification->fileUrl, $img);
            $html = sprintf($html, $link);
        } else {
            $html = sprintf($html, $img);
        }

        return $html;
    }
}
