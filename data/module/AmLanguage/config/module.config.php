<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'factories' => [
            \AmLanguage\Form\LanguageForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmLanguage\Form\LanguageFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ],
        'delegators' => [
            \AmLanguage\Form\LanguageFieldset::class => [
                \AmLanguage\Form\Factory\LanguageFieldsetDelegatorFactory::class
            ]
        ]
    ],
);