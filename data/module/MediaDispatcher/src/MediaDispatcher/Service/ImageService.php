<?php

namespace MediaDispatcher\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Intervention\Image\ImageManager;
use Zend\Filter\Dir;
use Zend\Filter\RealPath;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
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
    protected $documentRoot;

    /**
     * @var string
     */
    protected $imagePath;

    /**
     * @var string
     */
    protected $imageBackground = '#ffffff';

    /**
     * @var string
     */
    protected $cacheImagePath = null;

    /**
     * @var \Zend\Validator\File\IsImage
     */
    protected $isImageValidator;

    /**
     * @var \Zend\Filter\Dir
     */
    private $dirFilter;

    /**
     * @var \Zend\Filter\RealPath
     */
    private $realPathFilter;

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
        $this->serviceLocator = $container;

        $this->isImageValidator = new IsImage();
        $this->dirFilter = new Dir();
        $this->realPathFilter = new RealPath(false);

        $this->viewHelperManager = $container->get('ViewHelperManager');

        $this->imageManager = new ImageManager(['driver' => 'gd']);

        $this->documentRoot = $this->realPathFilter->filter($_SERVER['DOCUMENT_ROOT']);

        return $this;
    }

    /**
     * Valida que el path de la imagen que vamos a setear está dentro del path
     * permitido. Si encuentra la imagen, la ruta de dicha imagen se establece.
     * Si la imagen no la encuentra o se intenta acceder a una carpeta distinta,
     * da error.
     *
     * @param $imagePath
     * @param string $pathScope
     * @return $this
     * @throws \Exception
     */
    public function setImagePath($imagePath, $pathScope = DIRECTORY_SEPARATOR . 'media')
    {
        $realPathFilter = new RealPath(false);

        $realImagePath = $realPathFilter->filter($this->documentRoot . $imagePath);

        $cachePath = str_replace(
            $this->documentRoot . DIRECTORY_SEPARATOR . 'cache_media' . DIRECTORY_SEPARATOR,
            '',
            $realPathFilter->filter($this->documentRoot . '/cache_media' . $imagePath)
        );

        $imageDir = $this->dirFilter->filter($realImagePath);

        $intl = new Intl();

        if ($intl->strpos($imageDir, $this->documentRoot . $pathScope) === 0) {
            $docRootCacheImagePath = $this->realPathFilter->filter($this->documentRoot . '/cache_media/');

            $cacheImagePath = $docRootCacheImagePath . DIRECTORY_SEPARATOR . $cachePath;

            if ($this->isImageValidator->isValid($cacheImagePath) or $this->isImageValidator->isValid($realImagePath)) {
                $this->cacheImagePath = $cachePath;
                $this->imagePath = $realImagePath;
                return $this;
            }

            throw new \Exception('Imagen no válida');
        } else {
            throw new \Exception('Ruta de Imagen no válida');
        }
    }

    public function setImageBackground($color)
    {
        $this->imageBackground = $color;
    }

    public function createImage($width = null, $height = null, $clearCache = false)
    {
        $docRootCacheImagePath = $this->realPathFilter->filter($this->documentRoot . '/cache_media/');

        $cacheImagePath = $docRootCacheImagePath . DIRECTORY_SEPARATOR . $this->cacheImagePath;

        if (!$this->isImageValidator->isValid($cacheImagePath) or $clearCache) {
            $preventUpsize = function ($constraint) {
                $constraint->upsize();
            };

            $image = $this->imageManager->make($this->imagePath);

            if (!is_numeric($width)) {
                $width = $image->width();
            }
            if (!is_numeric($height)) {
                $height = $image->height();
            }

            $image->fit($width, $height, $preventUpsize);
            $image->resizeCanvas($width, $height, 'center', false, $this->imageBackground);

            $arrayDirs = explode(DIRECTORY_SEPARATOR, $this->dirFilter->filter($this->cacheImagePath));

            if (!is_dir($this->documentRoot . DIRECTORY_SEPARATOR . 'cache_media')) {
                mkdir($this->documentRoot . DIRECTORY_SEPARATOR . 'cache_media');
            }

            $this->createDirs($docRootCacheImagePath, $arrayDirs);

            $image->save($cacheImagePath, 80);
        }

        return $this->imageManager->make($cacheImagePath);
    }

    private function createDirs($path, &$newPath)
    {
        if (count($newPath) > 0) {
            if (!is_dir($path . DIRECTORY_SEPARATOR . $newPath[0])) {
                mkdir($path . DIRECTORY_SEPARATOR . $newPath[0]);
            }
            $path .= DIRECTORY_SEPARATOR . $newPath[0];
            unset($newPath[0]);
            $newPath = array_values($newPath);

            $this->createDirs($path, $newPath);
        }
    }
}
