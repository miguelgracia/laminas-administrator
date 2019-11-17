<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'aliases' => [
            'jobCategoriesId' => \Zend\Form\Element\Hidden::class,
        ],
        'factories' => [
            \AmJobCategory\Form\JobCategoryForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmJobCategory\Form\JobCategoryFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmJobCategory\Form\JobCategoryLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],

    'service_manager' => array(

    ),

    'router' => array(

    )
);