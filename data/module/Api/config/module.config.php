<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Api\Service\AppDataService::class => \Api\Service\ApiServiceFactory::class,
            \Api\Service\BlogService::class => \Api\Service\ApiServiceFactory::class,
            \Api\Service\BlogCategoryService::class => \Api\Service\ApiServiceFactory::class,
            \Api\Service\HomeModuleService::class => \Api\Service\ApiServiceFactory::class,
            \Api\Service\JobService::class => \Api\Service\ApiServiceFactory::class,
            \Api\Service\JobCategoryService::class => \Api\Service\ApiServiceFactory::class,
            \Api\Service\LanguageService::class => \Api\Service\ApiServiceFactory::class,
            \Api\Service\MegabannerService::class => \Api\Service\ApiServiceFactory::class,
            \Api\Service\SectionService::class => \Api\Service\ApiServiceFactory::class,
            \Api\Service\StaticPageService::class => \Api\Service\ApiServiceFactory::class,
            \Api\Service\PartnerService::class => \Api\Service\ApiServiceFactory::class,
            \Api\Service\ContactService::class => \Api\Service\ApiServiceFactory::class,
        ],
    ],
];
