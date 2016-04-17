<?php

namespace Gestor\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AppSettingsHelper extends AbstractHelper
{
    protected $settings = array();

    public function __invoke()
    {
        if (!$this->settings) {
            $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();
            $valoresConfiguracionTable = $sm->get('Gestor\Model\ValoresConfiguracionTable');

            $this->settings = $valoresConfiguracionTable->select()->toKeyValueArray('entryKey','entryValue');
        }
        return $this;
    }

    public function __get($value)
    {
        return isset($this->settings[$value]) ? $this->settings[$value] : null;
    }
}