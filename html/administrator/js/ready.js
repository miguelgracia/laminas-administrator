$(document).ready(function () {

    moment.locale('es');

    $.AdminLTE.srRoutes({
        '/home': function() {},
        '/admin/module':                 ['module','index'],
        '/admin/user':                   ['user','index'],
        '/admin/profile':                ['profile','index'],
        '/admin/menu/edit/{:num}':       ['menu','addAndedit'],
        '/admin/menu/add/{:num}':        ['menu','addAndedit'],
        '/admin/menu':                   ['menu','index'],
        '/admin/blog/edit/{:num}':       ['blog','edit'],
        '/admin/blog':                   ['blog','index'],
        '/admin/blog-category':          ['blog_category','index'],
        '/admin/megabanner':             ['megabanner','index'],
        '/admin/static-page':            ['static_page','index'],
        '/admin/job':                    ['job','index'],
        '/admin/job-category':           ['job_category','index'],
        '/admin/section':                ['section','index'],
        '/admin/language':               ['language','index'],
        '/admin/home-module':            ['home_module','index'],
        '/admin/app-data':               ['app_data','index'],
        '/admin/partner':                ['partner','index'],
        '/admin/media/videoPoster':      ['video_poster','index']
    }).run();
});