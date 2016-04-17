<?php

namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

class HomeController extends AuthController implements ControllerInterface
{
    protected $pdfService;

    public function setControllerVars()
    {
        $this->pdfService = $this->getServiceLocator()->get('PdfService');
    }

    public function indexAction()
    {
        $config = $this->sm->get('Config');
        $pathUpload = $this->config['pathbase_upload'].$config['path_guia'];
        $nombreFichero = $this->config['nombre_guia'];

        return new ViewModel(array(
            'pathUpload'        => $pathUpload,
            'nombreFichero'     => $nombreFichero,
            'path_guia'         => $config['path_guia'],
        ));

    }

    public function pdf1Action()
    {
        $this->pdfService->addPdfData(array(
            'texto' => 'holaaaa 1'
        ));

        echo $this->pdfService->render('mi_documento_1',true);

        die;
    }
}