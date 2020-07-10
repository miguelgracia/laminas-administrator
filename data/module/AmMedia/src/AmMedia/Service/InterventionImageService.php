<?php

namespace AmMedia\Service;

use Interop\Container\ContainerInterface;
use Intervention\Image\ImageManager;
use Laminas\ServiceManager\Factory\FactoryInterface;

class InterventionImageService implements FactoryInterface
{
    protected $manager;

    /**
     * Create service
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ImageManager(['driver' => 'gd']);
    }
}
