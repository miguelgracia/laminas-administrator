<?php
namespace AmMedia\Service;


use Intervention\Image\ImageManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InterventionImageService implements FactoryInterface
{
    protected $manager;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ImageManager(array('driver' => 'gd'));
    }
}