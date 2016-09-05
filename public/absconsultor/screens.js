var page = require('webpage').create();
page.viewportSize = {
    width: 1280,
    height: 1024
};
var baseUrl = 'http://absconsultor.local:8080';

var screenPages = [
    {
        url: baseUrl,
        imageName: 'home.png'
    },
    {
        url: baseUrl + "/empresa",
        imageName: 'empresa.png'
    },
    {
        url: baseUrl + "/trabajos",
        imageName: 'trabajos.png'
    },
    {
        url: baseUrl + "/trabajos/categoria/detalle",
        imageName: 'trabajos-detalle.png'
    },
    {
        url: baseUrl + "/contacto",
        imageName: 'contacto.png'
    },
    {
        url: baseUrl + "/empresa/colaboradores",
        imageName: 'colaboradores.png'
    }
];

page.open(screenPages[3].url, function() {
    page.render('screens/' + screenPages[3].imageName);
    phantom.exit();
});