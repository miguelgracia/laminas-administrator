<?php

namespace AmMedia\Model;

use Zend\Validator\File;

class ThumbModel
{

    /**
     * @var
     */
    protected $uploadDir;

    /**
     * @var
     */
    protected $ThumbResize;


    /**
     * @var
     */
    protected $ThumbService;


    public function ThumbModel($fileName){

        $ThumbSize    = $this->ThumbResize;
        $UploadDir    = $this->uploadDir;
        $file         = $UploadDir . DIRECTORY_SEPARATOR . $fileName;

        $fileTransfer = new File\NotExists($file);
        if(!$fileTransfer->isValid($file)){
            throw new Exception\DomainException(
                sprintf($file.' file not found.')
            );
        }

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if($extension == 'jpg' or $extension == 'jpeg' or $extension == 'gif' or $extension == 'png'){

            $file = str_replace('/',DIRECTORY_SEPARATOR,$file);

            $ThumbService = $this->ThumbService->make($file);

            foreach($ThumbSize as $key => $size)
            {
                if(!isset($size[0])){
                    throw new Exception\DomainException(
                        sprintf('Config Size not found.')
                    );
                }

                $image = $ThumbService->resize(@$size[0],@$size[1],function($constraint){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                if (!is_dir($UploadDir . DIRECTORY_SEPARATOR . $key)) {
                    mkdir($UploadDir . DIRECTORY_SEPARATOR . $key);
                }

                $image->save(
                        $UploadDir .
                        DIRECTORY_SEPARATOR .
                        $key . DIRECTORY_SEPARATOR .
                        $fileName
                );
                @chmod($UploadDir.$size.$fileName, 0777);
            }
        }
    }


    /**
     * @param $uploadDir
     * @return $this
     */
    public function setUploadDir($uploadDir)
    {
        $this->uploadDir = $uploadDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    /**
     * @param array $ThumbResize
     * @return $this
     */
    public function setThumbResize($ThumbResize)
    {
        $this->ThumbResize = $ThumbResize;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbResize()
    {
        return $this->ThumbResize;
    }

    /**
     * @param $ThumbService
     * @return $this
     */
    public function setThumbService($ThumbService)
    {
        $this->ThumbService = $ThumbService;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbService()
    {
        return $this->ThumbService;
    }

}
