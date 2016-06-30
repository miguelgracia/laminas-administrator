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
        '/admin/megabanner':             ['megabanner','index']
    }).run();
});