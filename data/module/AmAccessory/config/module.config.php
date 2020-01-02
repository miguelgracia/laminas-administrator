<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'aliases' => [
            'showInHome' => \Administrator\Form\Element\YesNoSelect::class
        ],
        'factories' => [
            \AmAccessory\Form\Element\AccessoryCategoriesId::class => \AmAccessory\Form\Element\AccessoryCategoriesIdFactory::class,
            \AmAccessory\Form\AccessoryForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmAccessory\Form\AccessoryFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmAccessory\Form\AccessoryLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],
];
