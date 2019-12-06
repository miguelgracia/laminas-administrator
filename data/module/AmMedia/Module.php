<?php

namespace AmMedia;

use Autoload\ModuleConfigTrait;

class Module
{
    use ModuleConfigTrait;
    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'AmMedia\Model\MediaModel'   => 'AmMedia\Model\MediaModel',
            ),
            'factories' => array(
                'AmMedia\Service\InterventionImageService' => 'AmMedia\Service\InterventionImageService',
            ),
        );
    }
}