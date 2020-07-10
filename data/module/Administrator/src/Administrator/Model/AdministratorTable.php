<?php

namespace Administrator\Model;

use Application\Model\MyOrmTableTrait;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\TableGateway\AbstractTableGateway;

abstract class AdministratorTable extends AbstractTableGateway implements AdapterAwareInterface
{
    use MyOrmTableTrait;

    public function save($data, $id = 0, $fieldKey = 'id')
    {
        if ($data instanceof AdministratorModel) {
            $data = $data->prepareToSave();

            if (isset($data[$fieldKey])) {
                $id = $data[$fieldKey];
                unset($data[$fieldKey]);
            }
        }

        if ($id == 0) {
            $this->insert($data);
            $id = $this->getLastInsertValue();
        } else {
            if ($this->isTableRow($id, $fieldKey)) {
                $this->update($data, [$fieldKey => $id]);
            } else {
                throw new \Exception($this->table . ' ' . $fieldKey . ' id does not exist');
            }
            $this->update($data, [$fieldKey => $id]);
        }
        return $id;
    }
}
