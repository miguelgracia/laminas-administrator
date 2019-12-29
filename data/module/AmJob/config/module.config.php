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
            \AmJob\Form\Element\JobCategoriesId::class => \AmJob\Form\Element\JobCategoriesIdFactory::class,
            \AmJob\Form\JobForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmJob\Form\JobFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmJob\Form\JobLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],
];
