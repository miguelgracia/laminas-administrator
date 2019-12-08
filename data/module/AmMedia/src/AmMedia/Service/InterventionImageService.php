<?php

namespace AmMedia\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Intervention\Image\ImageManager;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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
