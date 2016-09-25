<?php
namespace AmMedia\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\File\MimeType;
use Zend\Validator\ValidatorInterface;

class ScanDirService implements FactoryInterface
{
    protected $filter;

    protected $files = array();

    public function createService(ServiceLocatorInterface $serviceLocator)
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