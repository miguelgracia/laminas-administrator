<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 10/09/2016
 * Time: 0:13
 */

namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class SocialIcon extends AbstractHelper
{
    protected $socialIcons = [
        'facebook' => [
            'icon' => 'icon-facebook'
        ],
        'linkedin' => [
            'icon' => 'icon-linkedin'
        ],
        'instagram' => [
            'icon' => 'icon-instagram'
        ],
        'twitter' => [
            'icon' => 'icon-twitter'
        ],
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
                    '<li><a href="%s" target="_blank"><i class="%s"></i></a></li>',
                    $data->{$name},
                    $socialInfo['icon']
                );
            }
        }
    }
}
