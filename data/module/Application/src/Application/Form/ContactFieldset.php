<?php
namespace Application\Form;

use Zend\Filter\HtmlEntities;
use Zend\Filter\StringTrim;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Text;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\EmailAddress;

class ContactFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function __construct($name, array $options)
    {
        parent::__construct($name, $options);

        $this->add(array(
            'name' => 'name',
            'type' => Text::class,
            'attributes' => array(
                'id' => 'name',
                'class' => 'form-control'
            ),
        ))->add(array(
            'name' => 'email',
            'type' => Text::class,
            'attributes' => array(
                'id' => 'email',
                'class' => 'form-control'
            ),
        ))->add(array(
            'name' => 'phone',
            'type' => Text::class,
            'attributes' => array(
                'id' => 'phone',
                'class' => 'form-control'
            ),
        ))->add(array(
            'name' => 'message',
            'type' => Text::class,
            'attributes' => array(
                'id' => 'message',
                'class' => 'form-control',
                'rows' => '10'
            ),
        ))->add(array(
            'name' => 'legal',
            'type' => Checkbox::class,
            'attributes' => array(
                'id' => 'legal'
            ),
            'options' => array(
                'use_hidden_element' => false
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'email' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => StringTrim::class
                    ),
                    array(
                        'name' => HtmlEntities::class
                    )
                ),
                'validators' => array(
                    array(
                        'name' => EmailAddress::class
                    )
                )
            ),
            'message' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => StringTrim::class
                    ),
                    array(
                        'name' => HtmlEntities::class
                    )
                )
            ),
            'legal' => array(
                'required' => true
            )
        );
    }

}