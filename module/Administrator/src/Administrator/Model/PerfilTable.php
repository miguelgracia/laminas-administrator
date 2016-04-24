<?php

namespace Administrator\Model;

class PerfilTable extends AdministratorTable
{
    protected $table = "gestor_perfiles";

     private function recursivoFetchHijos($id, &$resultSet = array())
    {
        // Sacamos los hijos
        $auxResultSet = $this->select(array('gestor_perfil_id_padre' => $id));

        if ($auxResultSet->count()) {
            foreach ($auxResultSet as $row) {
                $resultSet[] = $row->getArrayCopy();
                $this->recursivoFetchHijos($row->id, $resultSet);
            }
        }
    }

    public function fetchHijos($id)
    {
        $this->recursivoFetchHijos($id, $dataSource);

        $resultSet = $this->getResultSetPrototype();
        $resultSet->initialize($dataSource);

        return $resultSet;
    }



    public function fetchId($id)
    {
        $resultSet = $this->select(array('id' => $id));
        return $resultSet;
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function fetchWhere($where = array())
    {
        return $this->select($where);
    }

    public function getPerfil($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePerfil(GestorModel $perfil)
    {
        $data = array(
            'nombre'                    => $perfil->nombre,
            'descripcion'               => $perfil->descripcion,
            'gestor_perfil_id_padre'    => $perfil->gestorPerfilIdPadre,
        );

        $id = (int) $perfil->id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getPerfil($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Perfil id does not exist');
            }
        }
    }

    public function deletePerfil($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}