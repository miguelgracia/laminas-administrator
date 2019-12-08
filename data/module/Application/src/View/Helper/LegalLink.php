<?php

namespace Application\View\Helper;

use Zend\Stdlib\ArrayObject;
use Zend\View\Helper\AbstractHelper;

class LegalLink extends AbstractHelper
{
    public function render($data, $lang)
    {
        $url = $this->view->plugin('Url');

        if (count($data['rows']) > 0) {
            foreach ($data['locale'][$lang] as $uriSegment => $locale) {
                if (is_array($locale) and isset($data['rows'][$locale['relatedTableId']])) {
                    echo sprintf(
                        '<a class="legal-link" href="%s">%s</a>',
                        $url('locale/legal/page', [
                            'locale' => $lang,
                            'page' => $locale['urlKey']
                        ]),
                        $locale['title']
                    );
                }
            }
        }
    }
}
