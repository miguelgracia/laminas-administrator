<?php
namespace AmMedia\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Validator\File\MimeType;

class ScanDirService implements FactoryInterface
{
    protected $filter;

    protected $files = array();

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $this;
    }

    public function getFiles($arrayMime = array())
    {
        $files = $this->files;

        $mimeTypeValidator = new MimeType($arrayMime);

        foreach ($files as $index => $file) {
            if (!$mimeTypeValidator->isValid($file)) {
                unset($files[$index]);
            }
        }

        return $files;
    }

    public function scan($currentDirectory)
    {
        $this->_scan($currentDirectory,$this->files);
    }

    private function _scan ($currentDirectory, &$files = array()) {

        if (is_array($currentDirectory)) {
            $dirs = $currentDirectory;
            foreach ($dirs as $dir) {
                $this->scan($dir,$files);
            }
        } else {
            $dirs = scandir($currentDirectory);

            foreach ($dirs as $dir) {
                if(!in_array($dir,array('.','..')) and strpos($dir,'_') !== 0 ) {
                    $checkString = $currentDirectory.'/'.$dir;

                    if (is_dir($checkString)) {
                        $this->scan($checkString, $files);
                    } elseif(is_file($checkString)) {
                        $files[] = $checkString;
                    }
                }
            }
        }
    }
}