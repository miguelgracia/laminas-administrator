<?php

namespace MediaDispatcher\Service;


use Intervention\Image\ImageManager;
use Zend\Filter\Dir;
use Zend\Filter\RealPath;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\StringWrapper\Intl;
use Zend\Validator\File\IsImage;

class ImageService implements FactoryInterface
{
    protected $serviceLocator;

    protected $viewHelperManager;

    /**
     * @var \Intervention\Image\ImageManager
     */
    protected $imageManager;

    /**
     * @var string
     */
    protected $imagePath;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $this->viewHelperManager = $serviceLocator->get('ViewHelperManager');

        $this->imageManager = new ImageManager(array('driver' => 'GD'));
        return $this;
    }

    public function setImagePath($imagePath, $pathScope = DIRECTORY_SEPARATOR.'media')
    {
        $realPathFilter = new RealPath(false);

        $rootDir = $realPathFilter->filter($_SERVER['DOCUMENT_ROOT']);
        $realImagePath = $realPathFilter->filter($rootDir.$imagePath);

        $dir = new Dir();
        $imageDir = $dir->filter($realImagePath);

        $intl = new Intl();

        if ($intl->strpos($imageDir, $rootDir.$pathScope) === 0 ) {
            $isImage = new IsImage();

            if ($isImage->isValid($realImagePath)) {
                $this->imagePath = $realImagePath;
                return $this;
            }

            throw new \Exception("Imagen no válida");

        } else {
            throw new \Exception("Ruta de Imagen no válida");
        }
    }

    public function createImage($width, $height)
    {
        $preventUpsize = function ($constraint) {
            $constraint->upsize();
        };

        $image = $this->imageManager->make($this->imagePath);

        $image->widen($width,$preventUpsize);
        $image->heighten($height,$preventUpsize);

        $image->resizeCanvas($width,$height);

        return $image;
    }

    public function makeUrl($imagePath)
    {
        $url = $this->viewHelperManager->get('Url');

        return $url('dispatch/random',array(
            'rnd' => microtime(true) * 10000
        ),array(
            'query' => array(
                'path' => $imagePath
            ),
            'force_canonical' => true
        ));
    }
}