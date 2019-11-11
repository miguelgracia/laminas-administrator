<?php


namespace Administrator\Traits;

/**
 * Trait ServiceLocatorAwareTrait
 * @package Administrator\Traits
 *
 * TRAIT TEMPORAL. ELIMINAR SU USO TAN PRONTO COMO SEA POSIBLE. EN LA VERSION ZF3 HA DESAPARECIDO
 *
 *
 */
trait ServiceLocatorAwareTrait
{
    protected $serviceLocator;


    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param mixed $serviceLocator
     * @return ServiceLocatorAwareTrait
     */
    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
}