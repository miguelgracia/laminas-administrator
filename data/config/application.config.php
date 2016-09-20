<?php
return array(
    'modules' => array(
        'Application',
        'MediaDispatcher',
        'Administrator',
        'AmLogin',
        'AmHome',
        'AmConfiguration',
        'AmUser',
        'AmProfile',
        'AmModule',
        'AmMenu',
        'AmTool',
        'AmBlog',
        'AmBlogCategory',
        'AmMedia',
        'AmMegabanner',
        'AmStaticPage',
        'AmJob',
        'AmJobCategory',
        'AmSection',
        'AmJobVideo',
        'AmLanguage',
        'AmHomeModule',
        'AmAppData',
        'Api',
        'AmPartner',
    ),
    'hidden_modules' => array(
        'AmLogin',
        'AmTool',
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './data/module',
            './data/vendor',
        ),
        'config_glob_paths' => array(
            'data/config/autoload/{{,*.}global,{,*.}local}.php',
        ),
    ),
);
