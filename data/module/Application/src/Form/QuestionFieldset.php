<?php

namespace Application\Form;

use Application\Validator\Recaptcha;
use Laminas\Filter\StringTrim;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator\Db\RecordExists;
use Laminas\Validator\EmailAddress;

class QuestionFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $adapter;

    public function __construct($name, array $options, $adapter)
    {
        parent::__construct($name, $options);

        $this->adapter = $adapter;

        $this->add([
            'name' => 'question_name',
            'type' => Text::class,
            'attributes' => [
                'id' => 'question_name',
                'class' => 'form-control',
                'placeholder' => 'Nombre'
            ],
        ])->add([
            'name' => 'question_email',
            'type' => Text::class,
            'attributes' => [
                'id' => 'question_email',
                'class' => 'form-control',
                'placeholder' => 'Email'
            ],
        ])->add([
            'name' => 'question_code',
            'type' => Text::class,
            'attributes' => [
                'id' => 'question_code',
                'class' => 'form-control',
                'placeholder' => 'Código de cliente'
            ],
        ])->add([
            'name' => 'question_topic',
            'type' => Select::class,
            'attributes' => [
                'id' => 'question_topic',
                'class' => 'form-control'
            ],
            'allow_empty' => false,
            'options' => [
                'empty_option' => 'Temática de la pregunta',
                'value_options' => [
                    'tema1' => 'tema1',
                    'tema2' => 'tema2',
                    'tema3' => 'tema3',
                    'tema4' => 'tema4',
                    'tema5' => 'tema5',
                ]
            ],
        ])
            ->add([
            'name' => 'question_message',
            'type' => Text::class,
            'attributes' => [
                'id' => 'question_message',
                'class' => 'form-control',
                'rows' => '10',
                'placeholder' => 'Mensaje'
            ],
        ])->add([
            'name' => 'question_legal',
            'type' => Checkbox::class,
            'attributes' => [
                'id' => 'question_legal'
            ],
            'options' => [
                'use_hidden_element' => false
            ]
        ])->add([
            'name' => 'g-recaptcha-response',
            'type' => Hidden::class,
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'question_name' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class
                    ]
                ]
            ],

            'question_email' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class
                    ]
                ],
                'validators' => [
                    [
                        'name' => EmailAddress::class
                    ]
                ]
            ],
            'question_topic' => [
                'required' => true,
            ],
            'question_code' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class
                    ]
                ],
                'validators' => [
                    [
                        'name' => RecordExists::class,
                        'options' => [
                            'table' => 'customers',
                            'field' => 'key',
                            'adapter' => $this->adapter
                        ]
                    ]
                ]
            ],
            'question_message' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class
                    ],
                ]
            ],
            'question_legal' => [
                'required' => true
            ],
            'g-recaptcha-response' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Recaptcha::class,
                        'options' => [
                            'captcha_secret' => $this->getOption('captcha_secret')
                        ]
                    ]
                ]
            ]
        ];
    }
}
