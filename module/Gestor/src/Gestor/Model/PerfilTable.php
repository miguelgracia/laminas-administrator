<?php

namespace Gestor\Model;

class PerfilTable extends GestorTable
{
    protected $table = "perfil";

    /* private function RecursivoFetchHijos($id, &$resultSet = array())
    {
        // Sacamos los hijos
        $auxResultSet = $this->select(array('idPerfilPadre' => $id));

        if ($auxResultSet->count()) {
            foreach ($auxResultSet as $row) {
                $resultSet[] = $row->getArrayCopy();
                $this->RecursivoFetchHijos($row->id, $resultSet);
            }
        }
    }

    public function fetchHijos($id)
    {
        $this->RecursivoFetchHijos($id, $dataSource);

        $resultSet = $this->getResultSetPrototype();
        $resultSet->initialize($dataSource);

        return $resultSet;
    }*/

    private function RecursivoFetchHijos($id)
    {
        // Sacamos los hijos
        $resultSet = $this->select(array('idPerfilPadre' => $id));

        // Los convertimos en un array
        $arraySet = $resultSet->toArray(); //array_values(iterator_to_array($resultSet));

        // Recorremos el array
        for ($i = 0; $i < count($arraySet); $i++)
        {
            $resultSetTemp = $this->RecursivoFetchHijos($arraySet[$i]['id']);
            if (count($resultSetTemp) > 0)
            {
                $arraySet = array_merge($arraySet, $resultSetTemp);
            }
        }
        return($arraySet);
    }


    public function fetchHijos($id)
    {
        // Llamamos al metodo recursivo
        $dataSource = $this->RecursivoFetchHijos($id);

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
            'nombre' => $perfil->nombre,
            'descripcion'  => $perfil->descripcion,
            'idPerfilPadre' => $perfil->idPerfilPadre,
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