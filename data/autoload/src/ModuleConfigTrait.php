<?php

namespace Autoload;

trait ModuleConfigTrait
{
    private $__dir;

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include $this->__dir . '/config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     * @throws \ReflectionException
     */
    public function getAutoloaderConfig()
    {
        $reflectionClass = new \ReflectionClass($this);
        $namespace = $reflectionClass->getNamespaceName();
        $this->__dir = dirname($reflectionClass->getFileName());

        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    $namespace => $this->__dir . '/src/' . $namespace,
                ),
            ),
        );
    }
}