<?php

namespace Application\View\Helper;

class ContactForm extends FormHelper
{
    public function render($lang, $legal)
    {
        $this->setHelpers();

        $formCheckbox = $this->helperManager->get('formCheckbox');

        $url = $this->helperManager->get('Url');

        $this->form->prepare();
        $this->form->setAttribute('action', $url('locale/contact', ['locale' => $lang]));

        $fieldset = $this->form->get($this->fieldsetName);

        $formTag = $this->getFormWrapper();

        $groupTags = $this->getElement('formInput', 'name', 'Form Name');
        $groupTags .= $this->getElement('formInput', 'email', 'Form Email');
        $groupTags .= $this->getElement('formInput', 'phone', 'Form Phone');
        $groupTags .= $this->getElement('formInput', 'question_code', 'Form customer code');
        $groupTags .= $this->getElement('formSelect', 'question_topic', 'Form question topic');
        $groupTags .= $this->getElement('formTextarea', 'message', 'Form Message');
        $groupTags .= $this->getElement('formInput', 'g-recaptcha-response',false);

        $field = $fieldset->get('legal');

        $input = $formCheckbox($field);

        $termAndConditionsStr = $this->setLegalLink($lang, $legal);

        $label = sprintf(
            $this->getLabelWrapper(false),
            'legal',
            ($input . $termAndConditionsStr)
        );

        $groupTags .= sprintf($this->getFormGroupWrapper(), $field->getName(), $label, '');

        $groupTags .= "
            <div class='form-group'>
                <div class='row'>
                    <button type='submit' class='btn btn-primary btn-lg'>"
                        . $this->translator->translate('Form Send', 'frontend'). '
                    </button>
                </div>
            </div>'
        ;

        echo sprintf($formTag, $groupTags);
    }
}
