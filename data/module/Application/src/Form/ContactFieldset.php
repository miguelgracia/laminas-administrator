<?php

namespace Application\Form;

use Application\Validator\Recaptcha;
use Laminas\Filter\StringTrim;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator\Callback;
use Laminas\Validator\Db\RecordExists;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\IsImage;

class ContactFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $adapter;

    public function __construct($name, array $options, $adapter)
    {
        parent::__construct($name, $options);

        $this->adapter = $adapter;

        $this->add([
            'name' => 'name',
            'type' => Text::class,
            'attributes' => [
                'id' => 'name',
                'class' => 'form-control',
                'placeholder' => 'Nombre'
            ],
        ])->add([
            'name' => 'email',
            'type' => Text::class,
            'attributes' => [
                'id' => 'email',
                'class' => 'form-control',
                'placeholder' => 'Email'
            ],
        ])->add([
            'name' => 'phone',
            'type' => Text::class,
            'attributes' => [
                'id' => 'phone',
                'class' => 'form-control',
                'placeholder' => 'Teléfono'
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
                    'repuestos' => 'Repuestos',
                    'ingenieria_electrica' => 'Ingeniería Eléctrica',
                    'reparaciones' => 'Reparaciones',
                    'ingenieria_mecanica' => 'Ingeniería mecánica',
                    'otros' => 'otros',
                ]
            ],
        ])->add([
            'name' => 'message',
            'type' => Text::class,
            'attributes' => [
                'id' => 'message',
                'class' => 'form-control',
                'rows' => '10',
                'placeholder' => 'Mensaje'
            ],
        ])->add([
            'name' => 'file',
            'type' => File::class,
            'attributes' => [
                'id' => 'file',
                'class' => 'form-control',
                'multiple' => true
            ],
        ])->add([
            'name' => 'question_code',
            'type' => Text::class,
            'attributes' => [
                'id' => 'question_code',
                'class' => 'form-control',
                'placeholder' => 'Código de cliente (opcional)'
            ],
        ])->add([
            'name' => 'legal',
            'type' => Checkbox::class,
            'attributes' => [
                'id' => 'legal'
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
            'name' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class
                    ]
                ]
            ],

            'email' => [
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
            'message' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class
                    ],
                ]
            ],
            'question_topic' => [
                'required' => true,
            ],
            'question_code' => [
                'required' => false,
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
            'file' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => FilesSize::class,
                        'options' => [
                            /**
                             * TODO: parametrizar este valor para que vaya acorde al string que se encuentra a pelo
                             * en el archivo module/Application/languages/es.php
                             */
                            'max' => (1024 * 4) . 'kB',
                            'messages' => [
                                /**
                                 * TODO: Buscar la forma de no tener que estar poniendo los mnesajes de error con variables
                                 * desde aqui para que lo pille en espa;ol
                                 */
                                FilesSize::TOO_BIG => _("El conjunto de archivos debería tener un tamaño máximo de '%max%' pero tiene un tamaño de '%size%'")
                            ]
                        ]
                    ],
                    [
                        /**
                         * Devemos usar el validador de tipo Callback porque el validador IsImage no admite la variable
                         * $_FILES con multiples archivos
                         */
                        'name' => Callback::class,
                        'options' => [
                            'callback' => function ($files) {
                                foreach ($files as $file) {
                                    if (!(new IsImage)->isValid($file)) {
                                        return false;
                                    }
                                }
                                return true;
                            },
                            'messages' => [
                                Callback::INVALID_VALUE => _("Lo sentimos. Solo es posible subir archivos de imagen.")
                            ]
                        ],
                    ]
                ]
            ],
            'legal' => [
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
