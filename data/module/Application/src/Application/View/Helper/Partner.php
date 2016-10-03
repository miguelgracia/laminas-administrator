<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class Partner extends AbstractHelper
{
    protected $lang;

    function __invoke($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    private function getElementCarousel()
    {
        return "<div class='item'>
                    <a target='_blank' href='%s'><img src='%s' alt='%s'></a>
                </div>";
    }

    public function render($partners)
    {
        $html = '';

        foreach ($partners as $index => $partner) {

            if ($partner->logo != '') {

                $html .= sprintf(
                    $this->getElementCarousel(),
                    $partner->website,
                    '/media'.$partner->logo,
                    $partner->name
                );
            }
        }

        echo $html;
    }
}