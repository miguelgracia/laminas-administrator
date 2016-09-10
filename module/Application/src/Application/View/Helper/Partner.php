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
                    <a href='%s'><img src='%s' alt='%s'></a>
                </div>";
    }

    private function getWebsiteButton()
    {
        return "<a href='%s' class='btn btn-default' target='_blank'>".$this->translator->translate('More info','frontend')."</a>";
    }

    public function getContentWrapper()
    {
        return "<div class='row list-item collaborator'>
                <div class='col-md-3'>
                    <img class='img-responsive' src='%s' alt='%s'>
                </div>
                <div class='col-md-9'>
                    <h3>%s</h3>
                    %s
                    %s
                </div>
            </div>";
    }

    public function render($partners, $forCarousel = true)
    {
        $url = $this->getView()->getHelperPluginManager()->get('Url');

        $html = '';

        foreach ($partners as $index => $partner) {

            if ($forCarousel and $partner->logo != '') {

                $html .= sprintf(
                    $this->getElementCarousel(),
                    $url($this->lang.'/company/colaborators',array(),array('fragment' => $partner->name)),
                    '/media'.$partner->logo,
                    $partner->name
                );

            } elseif (!$forCarousel) {
                $html .= sprintf(
                    $this->getContentWrapper(),
                    '/media'.$partner->logo,
                    $partner->name,
                    $partner->name,
                    $partner->locale->content,
                    ($partner->website != '' ? sprintf($this->getWebsiteButton(),$partner->website) : '')
                );
            }
        }

        echo $html;
    }
}