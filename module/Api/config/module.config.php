<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Application\Api'              => 'Api\Service\ApiService',
            'Application\Api\AppData'      => 'Api\Service\AppDataService',
            'Application\Api\Blog'         => 'Api\Service\BlogService',
            'Application\Api\BlogCategory' => 'Api\Service\BlogCategoryService',
            'Application\Api\HomeModule'   => 'Api\Service\HomeModuleService',
            'Application\Api\Job'          => 'Api\Service\JobService',
            'Application\Api\JobCategory'  => 'Api\Service\JobCategoryService',
            'Application\Api\Language'     => 'Api\Service\LanguageService',
            'Application\Api\Megabanner'   => 'Api\Service\MegabannerService',
            'Application\Api\Section'      => 'Api\Service\SectionService',
            'Application\Api\StaticPage'   => 'Api\Service\StaticPageService',
            'Application\Api\Partner'      => 'Api\Service\PartnerService',
        )
    ),
);