<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 10/09/2016
 * Time: 0:13
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SocialIcon extends AbstractHelper
{
    protected $socialIcons = [
        'facebook' => [
            'icon' => 'fa-facebook-official'
        ],
        'twitter' => [
            'icon' => 'fa-twitter'
        ],
        'googlePlus' => [
            'icon' => 'fa-google-plus'
        ],
        'instagram' => [
            'icon' => 'fa-instagram'
        ]
    ];

    public function __invoke($data = [])
    {
        if (count($data) > 0) {
            $this->render($data);
        }
        return $this;
    }

    public function render($data = [])
    {
        foreach ($this->socialIcons as $name => $socialInfo) {
            if (property_exists($data, $name) and trim($data->{$name}) != '') {
                echo sprintf(
                    '<a href="%s" target="_blank"><i class="fa %s"></i></a>',
                    $data->{$name},
                    $socialInfo['icon']
                );
            }
        }
    }
}
