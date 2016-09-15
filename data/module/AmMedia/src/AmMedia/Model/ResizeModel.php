<?php

namespace AmMedia\Model;

use Zend\Validator\File;

class ResizeModel
{
    /**
     * @var
     */
    protected $uploadDir;

    /**
     * @var
     */
    protected $resize;

    /**
     * @var
     */
    protected $resizeService;

    public function resize($fileName, $model)
    {
        $fileSizes    = $this->resize[$model];
        $uploadDir    = $this->uploadDir;
        $file         = $uploadDir . DIRECTORY_SEPARATOR . $model. DIRECTORY_SEPARATOR . $fileName;

        $fileTransfer = new File\NotExists($file);

        if(!$fileTransfer->isValid($file)){
            throw new Exception\DomainException(
                sprintf($file.' file not found.')
            );
        }

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        $validExtensions = [
            'jpg','jpeg','gif','png'
        ];

        if(in_array($extension, $validExtensions)){

            $file = str_replace('/',DIRECTORY_SEPARATOR,$file);

            $ThumbService = $this->resizeService->make($file);

            foreach($fileSizes as $key => $size)
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

                if (!is_dir($uploadDir . DIRECTORY_SEPARATOR . $model)) {
                    mkdir($uploadDir . DIRECTORY_SEPARATOR . $model);
                }

                if (!is_dir($uploadDir . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $key)) {
                    mkdir($uploadDir . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $key);
                }

                $image->save(
                        $uploadDir .
                        DIRECTORY_SEPARATOR .
                        $model . DIRECTORY_SEPARATOR .
                        $key . DIRECTORY_SEPARATOR .
                        $fileName
                );
                @chmod($uploadDir.$size.$fileName, 0777);
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
     * @param array $resize
     * @return $this
     */
    public function setResize($resize)
    {
        $this->resize = $resize;
        return $this;
    }

    /**
     * @return string
     */
    public function getResize()
    {
        return $this->resize;
    }

    /**
     * @param $ThumbService
     * @return $this
     */
    public function setResizeService($resizeService)
    {
        $this->resizeService = $resizeService;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbService()
    {
        return $this->resizeService;
    }
}