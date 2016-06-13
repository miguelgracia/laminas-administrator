<?php

namespace AmMedia\Service;

use Administrator\Model\AdministratorTable;
use AmMedia\Options\PluploadOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Administrator\EventManager\EventProvider;

class PluploadService extends EventProvider implements FactoryInterface
{
    protected $serviceLocator;

    /**
     * @var
     */
    protected $pluploadOptions;

    /**
     * @var
     */
    protected $pluploadModel;

    /**
     * @var
     */
    protected $pluploadEntity;

    /**
     * @var
     */
    protected $mediaTableGateway;

    /**
     * @var
     */
    protected $PluploadList;

    /**
     * @var
     */
    protected $resizeModel;

    /**
     * @var
     */
    protected $RemoveModel;

    /**
     * @var
     */
    protected $serviceManager;

    /**
     * @var string
     */
    protected $DirUploadAbsolute;

    /**
     * @var
     */
    protected $resize;

    /**
     * @var
     */
    protected $DirUpload;


    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * @param $id_parent
     * @param $data
     * @param $model
     * @return bool
     * @throws \Exception
     */
    public function upload($id_parent, $data, $model)
    {
        $this->getPluploadOptions();

        $mediaTableGateway      = $this->getTableGateway();
        $pluploadEntity      = $this->getPluploadEntity();
        $pluploadModel       = $this->getPluploadModel();
        $resizeModel         = $this->getResizeModel();

        $pluploadEntity
        ->setName(      (string) $data['file']['name'])
        ->setType(      (string) $data['file']['type'])
        ->setTmpName(   (string) $data['file']['tmp_name'])
        ->setError(     (int)    $data['file']['error'])
        ->setSize(      (int)    $data['file']['size'])
        ->setIdParent(  (int)    $id_parent)
        ->setModel(     (string) $model)
        ;
        $this->getEventManager()->trigger(__FUNCTION__.'.pre', $this, array('plupload_entity' => $pluploadEntity));

        if(isset($data["chunk"])) {

            $uploadDir = $pluploadModel->getUploadDir();

            $uploadDir .= DIRECTORY_SEPARATOR . $model;

            $pluploadModel->setUploadDir($uploadDir);

            $file = $pluploadModel->PluploadModel($data);

            if($file) {

                if(($data["chunk"]+1) == $data["chunks"]) {

                    // Get db last id
                    $id = $mediaTableGateway->save($pluploadEntity);

                    // Get size and rename
                    $fileSize = filesize ( $file['filePath'] );
                    $NameRename  =  str_replace(DIRECTORY_SEPARATOR.'-',DIRECTORY_SEPARATOR.$id.'-',$file['filePath']);

                    rename($file['filePath'],$NameRename);

                    // Thumb
                   $resizeModel->resize($id.$file['fileName'], $model);

                    // Update Db
                    $pluploadEntity
                        ->setName($id.$file['fileName'])
                        ->setSize($fileSize)
                        ->setId($id);
                    $mediaTableGateway->save($pluploadEntity);
                }
            } else {
                throw new \Exception('Not writable '.$this->getPluploadOptions()->DirUploadAbsolute);
            }
        } else {

            // Get db last id
            $id = $mediaTableGateway->save($pluploadEntity);

            // Upload and set Name
            $pluploadModel->setId($id);
            $file = $pluploadModel->PluploadModel($data);

            if ($file) {

                // Thumb
                $resizeModel->resizeModel($file['fileName']);

                // Update Db
                $pluploadEntity
                    ->setName($file['fileName'])
                    ->setId($id);
                $mediaTableGateway->save($pluploadEntity);

           } else {
                $mediaTableGateway->Remove($id);
                throw new \Exception('Not writable '.$this->getPluploadOptions()->DirUploadAbsolute);
            }
        }

        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('plupload_entity' => $pluploadEntity));

        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function PluploadRemove($id)
    {
        $this->getPluploadOptions();

        $mediaTableGateway      = $this->getTableGateway();
        $RemoveModel         = $this->getRemoveModel();

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('remove_model' => $RemoveModel));

        if($mediaTableGateway->find($id)) {
            $fileDb = $mediaTableGateway->find($id)->getName();
            if($RemoveModel->Remove($fileDb)) {
                $mediaTableGateway->Remove($id);
            }
        }

        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('remove_model' => $RemoveModel));

        return true;
    }

    /**
     * @param $model
     * @param $id_parent
     * @return bool
     */
    public function pluploadRemoveAll($model,$id_parent)
    {
        $mediaTableGateway  = $this->getTableGateway();
        $m = $mediaTableGateway->findByModel($model,$id_parent);

        if( $m ) {
            foreach($m as $r) {
                $this->PluploadRemove($r->getIdPlupload());
            }
        }
        return true;
    }

    /**
     * @param $model
     * @param $id_parent
     * @param $id
     * @return bool
     */
    public function pluploadUpdate($model,$id_parent,$id)
    {
        $mediaTableGateway      = $this->getTableGateway();
        $pluploadEntity      = $this->getPluploadEntity();
        $m = $mediaTableGateway->findByModel($model,$id_parent);
        if($m) {
            foreach($m as $r) {
                $pluploadEntity
                    ->setName($r->getName())
                    ->setType($r->getType())
                    ->setTmpName($r->getTmpName())
                    ->setError($r->getError())
                    ->setSize($r->getSize())
                    ->setIdParent($id)
                    ->setModel($r->getModel())
                    ->setIdPlupload($r->getIdPlupload());
                $mediaTableGateway->update($pluploadEntity);
            }
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getPluploadList()
    {
        if (!$this->PluploadList) {
            $this->setPluploadIdList(0);
        }
        return $this->PluploadList;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setPluploadIdList($id)
    {
        $this->PluploadList = $this->getTableGateway()->findByParent($id);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPluploadIdAndModelList()
    {
        if (!$this->PluploadList) {
            $this->setPluploadIdList(0);
        }
        return $this->PluploadList;
    }

    /**
     * GET OPTIONS
     * @return mixed
     */
    public function getPluploadOptions() {

        if (!$this->pluploadOptions) {
            $this->setPluploadOptions(
                $this->serviceLocator->get('plupload_options')
            );
        }

        return $this->pluploadOptions;
    }

    /**
     * SET OPTIONS
     * @param PluploadOptions $PluploadOptions
     * @return $this
     */
    public function setPluploadOptions(PluploadOptions $PluploadOptions) {

        $this->pluploadOptions = $PluploadOptions;

        return $this;
    }

    /**
     * GET TableGateway
     * @return mixed
     */
    public function getTableGateway()
    {
        if (!$this->mediaTableGateway) {
            $this->setTableGateway(
                $this->serviceLocator->get('AmMedia\Model\MediaTable')
            );
        }
        return $this->mediaTableGateway;
    }

    /**
     * SET TABLEGATEWAY
     * @param AdministratorTable $tableGateway
     * @return $this
     */
    public function setTableGateway(AdministratorTable $tableGateway)
    {
        $this->mediaTableGateway = $tableGateway;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPluploadModel() {

        if (!$this->pluploadModel) {
            $this->setPluploadModel(
                $this->serviceLocator->get('plupload_model')
            );
        }

        if(!$this->DirUpload) {
            $this->pluploadModel->setUploadDir($this->getPluploadOptions()->DirUploadAbsolute);
        }

        return $this->pluploadModel;
    }

    /**
     * @param $pluploadModel
     * @return $this
     */
    public function setPluploadModel($pluploadModel) {

        $this->pluploadModel = $pluploadModel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRemoveModel() {

        if (!$this->RemoveModel) {
            $this->setRemoveModel(
                $this->serviceLocator->get('remove_model')
            );
        }

        if(!$this->DirUpload) {
            $this->RemoveModel->setUploadDir($this->getPluploadOptions()->DirUploadAbsolute);
        }
        if(!$this->resize) {
            $this->RemoveModel->setThumbResize($this->getPluploadOptions()->resize);
        }

        return $this->RemoveModel;
    }

    /**
     * @param $RemoveModel
     * @return $this
     */
    public function setRemoveModel($RemoveModel) {
        $this->RemoveModel = $RemoveModel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResizeModel() {

        if (!$this->resizeModel) {
            $this->setThumbModel(
                $this->serviceLocator->get('resize_model')
            );
        }

        if(!$this->DirUpload) {
            $this->resizeModel->setUploadDir($this->getPluploadOptions()->DirUploadAbsolute);
        }
        if(!$this->resize) {
            $this->resizeModel->setResize($this->getPluploadOptions()->Resize);
        }

        return $this->resizeModel;
    }

    /**
     * @param $ThumbModel
     * @return $this
     */
    public function setThumbModel($ThumbModel) {
        $this->resizeModel = $ThumbModel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPluploadEntity() {

        if (!$this->pluploadEntity) {
            $this->setPluploadEntity(
                $this->serviceLocator->get('AmMedia\Model\MediaModel')
            );
        }
        return $this->pluploadEntity;
    }

    /**
     * @param $pluploadEntity
     * @return $this
     */
    public function setPluploadEntity($pluploadEntity) {
        $this->pluploadEntity = $pluploadEntity;
        return $this;
    }
}