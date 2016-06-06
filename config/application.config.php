<?php
return array(
    'modules' => array(
        'Application',
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
        'AmMedia'
    ),
    'hidden_modules' => array(
        'AmLogin',
        'AmTool',
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{{,*.}global,{,*.}local}.php',
        ),
    ),
);
