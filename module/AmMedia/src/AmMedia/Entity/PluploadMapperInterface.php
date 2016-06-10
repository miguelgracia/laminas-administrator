<?php
namespace AmMedia\Entity;

interface PluploadMapperInterface
{
    public function find($id);

    public function findByParent($id);

    public function findByParentByModel($id,$model);

    public function insert($plupload);

    public function update($plupload);

    public function remove($id);

    public function setTableName($pluploadTableName);
}
