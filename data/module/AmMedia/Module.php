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
        return [
            'invokables' => [
                'AmMedia\Model\MediaModel' => 'AmMedia\Model\MediaModel',
            ],
            'factories' => [
                'AmMedia\Service\InterventionImageService' => 'AmMedia\Service\InterventionImageService',
            ],
        ];
    }
}
