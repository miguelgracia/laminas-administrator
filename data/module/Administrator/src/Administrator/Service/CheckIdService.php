<?php


namespace Administrator\Service;


class CheckIdService
{
    /**
     * @var int[]
     */
    private $elementsId;

    /**
     * @param $formElement
     *
     * Comprobamos que los id's que se han asignado a los elementos de formulario no están repetidos
     * Si encontramos un caso en el que sí este, añadimos un prefijo al id
     * @return string
     */

    public function checkId($id)
    {
        if (array_key_exists($id, $this->elementsId)) {
            $this->elementsId[$id]++;
            $id = $id . "_" . $this->elementsId[$id];
        } else {
            $this->elementsId[$id] = 0;
        }

        return $id;
    }
}