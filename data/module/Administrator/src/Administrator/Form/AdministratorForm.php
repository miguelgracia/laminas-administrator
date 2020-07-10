<?php

namespace Administrator\Form;

use Laminas\Form\Form;

class AdministratorForm extends Form
{
    const ACTION_DEFAULT = 'index';
    const ACTION_ADD = 'add';
    const ACTION_EDIT = 'edit';
    const ACTION_DELETE = 'delete';

    public function init()
    {
        $formElemenetManager = $this->factory->getFormElementManager();
        $formInitializers = $this->initializers();

        foreach ($formInitializers['fieldsets'] as $fieldsetName) {
            $isLocale = strpos($fieldsetName, 'LocaleFieldset') !== false;

            if ($isLocale) {
                $localeFieldsets = $formElemenetManager->build($fieldsetName);

                foreach ($localeFieldsets as $localeFieldset) {
                    $this->add($localeFieldset);
                }

                continue;
            }

            $this->add($formElemenetManager->build($fieldsetName));
        }
    }
}
