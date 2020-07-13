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

        $groupTags = $this->getElement('formInput', 'name');
        $groupTags .= $this->getElement('formInput', 'email');
        $groupTags .= $this->getElement('formInput', 'phone');
        $groupTags .= $this->getElement('formInput', 'question_code');
        $groupTags .= $this->getElement('formSelect', 'question_topic');
        $groupTags .= $this->getElement('formTextarea', 'message');
        $groupTags .= $this->getElement('formInput', 'g-recaptcha-response');
        $groupTags .= $this->getElement('formFile', 'file', 'Form files');

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
                <div class='col-lg-offset-2 col-lg-10'>
                    <button type='submit' class='btn btn-primary btn-lg'>"
                        . $this->translator->translate('Form Send', 'frontend'). '
                    </button>
                </div>
            </div>';

        echo sprintf($formTag, $groupTags);
    }
}
