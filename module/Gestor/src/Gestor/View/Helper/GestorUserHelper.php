<?php

namespace Gestor\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GestorUserHelper extends AbstractHelper
{
    protected $serviceLocator;

    function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator->getServiceLocator();
    }

    public function __invoke()
    {
        /// Sacamos datos del usuario
        $authService = $this->serviceLocator->get('AuthService');
        $identity = $authService->getIdentity();

        $gestorUsuariosTable = $this->serviceLocator->get('Gestor\Model\GestorUsuariosTable');
        $dataUser = $gestorUsuariosTable->select(array(
            'login' => $identity['user']
        ))->current();

        /*
         * Devuelve algo tipo
         *
         * object(Gestor\Model\GestorUsuarios)[304]
              public 'id' => string '1' (length=1)
              public 'login' => string 'dreamsite' (length=9)
              public 'password' => string 'c4e3bfab1234a7476781bd8e03daa919' (length=32)
              public 'fechaAlta' => string '2015-12-04 10:36:30' (length=19)
              public 'ultimo_login' => null
              public 'idPerfil' => string '1' (length=1)
              public 'ultimoLogin' => string '2015-12-10 16:32:32' (length=19)
         */

        return $dataUser;
    }
}