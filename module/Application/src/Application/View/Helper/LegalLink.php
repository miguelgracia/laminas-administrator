<?php

namespace Application\View\Helper;

use Zend\Stdlib\ArrayObject;
use Zend\View\Helper\AbstractHelper;

class LegalLink extends AbstractHelper
{
    public function __invoke()
    {
        return $this;
    }

    public function render(ArrayObject $data, $lang)
    {
        $url = $this->view->plugin('Url');

        foreach ($data->locale->{$lang} as $uriSegment => $locale) {
            echo sprintf(
                '<a class="legal-link" href="%s">%s</a>',
                $url($lang.'/legal/page',array(
                    'page' => $locale->urlKey
                )),
                $locale->title
            );
        }
    }
}