<?php
namespace AmMedia\Entity;

use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;

class PluploadHydrator extends ClassMethods implements HydratorInterface
{

    /**
     * @param object $object
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function extract($object)
    {
        if (!$object instanceof PluploadEntity) {
            throw new Exception\InvalidArgumentException('$object must be an instance of Plupload\Entity\PluploadEntity');
        }
        $data = parent::extract($object);
        $data = $this->mapField('id_plupload', 'id', $data);
        return $data;
    }

    /**
     * @param array $data
     * @param object $object
     * @return object
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof PluploadEntity) {
            throw new Exception\InvalidArgumentException('$object must be an instance of Plupload\Entity\PluploadEntity');
        }
        $data = $this->mapField('id', 'id_plupload', $data);
        return parent::hydrate($data, $object);
    }

    protected function mapField($keyFrom, $keyTo, array $array)
    {
        $array[$keyTo] = $array[$keyFrom];
        unset($array[$keyFrom]);
        return $array;
    }
}