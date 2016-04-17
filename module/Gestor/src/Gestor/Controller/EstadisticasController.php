<?php
namespace Gestor\Controller;
use Zend\View\Model\ViewModel;
//use Gestor\Model\EstadisticasLoginsTable;
//use Gestor\Model\EstadisticasPdfTable;

class EstadisticasController extends AuthController implements ControllerInterface
{
    protected $datatable;
    protected $estadisticasloginsTable;
    protected $estadisticaspdfTable;
    protected $directorrelacionTable;
    protected $gestorusuariosTable;

    public function setControllerVars()
    {
        $this->datatable = $this->sm->get('Gestor\Service\DatatableService');
        $this->estadisticasloginsTable = $this->sm->get('Gestor\Model\EstadisticasLoginsTable');
        $this->estadisticaspdfTable = $this->sm->get('Gestor\Model\EstadisticasPdfTable');
        $this->directorRelacionTable = $this->sm->get('Gestor\Model\DirectorRelacionTable');
        $this->gestorusuariosTable = $this->sm->get('Gestor\Model\GestorUsuariosTable');
    }

    public function indexAction()
    {

        // Primero sacamos un array de los DRs
        $arrayDRs = $this->directorRelacionTable->fetchAllData();

        $request = $this->getRequest();
        if (($request->isPost()) && ($_REQUEST['fechas'] != '')) {

            // Sacamos la fecha de inicio y de fin OJO QUE ESTA EN FORMATO ASQUEROSO ANGLOSAJON
            $fechas = $_REQUEST['fechas'];
            //$fecha_inicio = substr($fechas, 0, 10);
            $fecha_inicio = substr($fechas,6,4)."-".substr($fechas,0,2)."-".substr($fechas,3,2);
            //$fecha_fin = substr($fechas, 13, 10);
            $fecha_fin = substr($fechas,19,4)."-".substr($fechas,13,2)."-".substr($fechas,16,2);

            // Sacamos las estadisticas de generación de PDFs en las fechas indicadas
            for ($i = 0; $i < count($arrayDRs); $i++) {
                $arrayPdf = $this->estadisticaspdfTable->ContarGeneradosFechas($arrayDRs[$i]->idusuario, $fecha_inicio, $fecha_fin);
                $arrayFinal[$i] = $arrayPdf;

                // Contamos el total de accesos entre las fechas indicadas
                $arrayFinal[$i]['cuantosAccesos'] = $this->estadisticasloginsTable->ContarAccesosFechas($arrayDRs[$i]->idusuario, $fecha_inicio, $fecha_fin);

                // TODO sacar esto de aqui y hacer el recorrido
                $ultimoAcceso = $this->gestorusuariosTable->getGestorUsuarios($arrayDRs[$i]->idusuario)->ultimoLogin;
                if ($ultimoAcceso != null) {
                    //$arrayFinal[$i]->ultimoAcceso = $ultimoAcceso;
                    $arrayFinal[$i]['ultimoAcceso'] = $ultimoAcceso;
                }
                $arrayFinal[$i]['nombre'] = $arrayDRs[$i]->nombre;
                $arrayFinal[$i]['apellido1'] = $arrayDRs[$i]->apellido1;
                $arrayFinal[$i]['apellido2'] = $arrayDRs[$i]->apellido2;
                $arrayFinal[$i]['nombreProvincia'] = $arrayDRs[$i]->nombreProvincia;
                $arrayFinal[$i]['numerooficina'] = $arrayDRs[$i]->numerooficina;
                $arrayFinal[$i]['direccion'] = $arrayDRs[$i]->direccion;
                $arrayFinal[$i]['localidad'] = $arrayDRs[$i]->localidad;
                $arrayFinal[$i]['cp'] = $arrayDRs[$i]->cp;
                $arrayFinal[$i]['nombreDR'] = $arrayDRs[$i]->nombreDR;
                $arrayFinal[$i]['login'] = $arrayDRs[$i]->login;
            }

        } else {

            // Lo sacamos sin fechas

            // Ahora por cada uno de ellos vamos a sacar los datos que necesitamos
            for ($i = 0; $i < count($arrayDRs); $i++) {
                // Tomamos los datos que nos interesan de lo anterior
                // Ahora el numero de PDFs generados, y por tipo
                $arrayPdf = $this->estadisticaspdfTable->ContarGenerados($arrayDRs[$i]->idusuario);
                $arrayFinal[$i] = $arrayPdf;

                $arrayFinal[$i]['cuantosAccesos'] = $this->estadisticasloginsTable->ContarAccesos($arrayDRs[$i]->idusuario);

                // TODO sacar esto de aqui y hacer el recorrido
                $ultimoAcceso = $this->gestorusuariosTable->getGestorUsuarios($arrayDRs[$i]->idusuario)->ultimoLogin;
                if ($ultimoAcceso != null) {
                    //$arrayFinal[$i]->ultimoAcceso = $ultimoAcceso;
                    $arrayFinal[$i]['ultimoAcceso'] = $ultimoAcceso;
                }
                $arrayFinal[$i]['nombre'] = $arrayDRs[$i]->nombre;
                $arrayFinal[$i]['apellido1'] = $arrayDRs[$i]->apellido1;
                $arrayFinal[$i]['apellido2'] = $arrayDRs[$i]->apellido2;
                $arrayFinal[$i]['nombreProvincia'] = $arrayDRs[$i]->nombreProvincia;
                $arrayFinal[$i]['numerooficina'] = $arrayDRs[$i]->numerooficina;
                $arrayFinal[$i]['direccion'] = $arrayDRs[$i]->direccion;
                $arrayFinal[$i]['localidad'] = $arrayDRs[$i]->localidad;
                $arrayFinal[$i]['cp'] = $arrayDRs[$i]->cp;
                $arrayFinal[$i]['nombreDR'] = $arrayDRs[$i]->nombreDR;
                $arrayFinal[$i]['login'] = $arrayDRs[$i]->login;
            }
        }



        return new ViewModel(array(
            'arrayDatos' => $arrayFinal
        ));

    }

    public function testAction()
    {

        // TEST
        $dataLogin['idusuario'] = 11;
        $dataLogin['correcto'] = 1;
        $this->estadisticasloginsTable->GrabarLogin($dataLogin);

        $dataPdf['idusuario'] = 11;
        $dataPdf['idlenguaje'] = 1;
        $this->estadisticaspdfTable->GrabarPdfGenerado($dataPdf);

    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }
}