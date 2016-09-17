<?php

namespace Api\Service;

use Application\Form\ContactFieldset;
use Zend\Form\Form;
use Zend\ServiceManager\FactoryInterface;

class ContactService implements FactoryInterface
{
    use ApiServiceTrait;

    /**
     * @var \Zend\Form\Form
     */
    protected $form;

    public function createForm()
    {
        $fieldset = new ContactFieldset('contact', array());
        $this->form = new Form();
        $this->form->add($fieldset);
        $this->form->setAttribute('method', 'post');
        return $this->form;
    }

    public function bind($postData)
    {
        $this->form->setData($postData);
    }

    public function validate()
    {
        $this->form->isValid();

        return $this->form->getMessages();
    }
}