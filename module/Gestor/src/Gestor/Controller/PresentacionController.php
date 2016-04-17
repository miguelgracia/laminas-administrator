<?php
namespace Gestor\Controller;


use Zend\View\Model\ViewModel;
use Gestor\Form\PresentacionForm;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;


class PresentacionController extends AuthController implements ControllerInterface
{
    protected $datatable;
    protected $ultimosdatosTable;
    protected $equipoTable;
    protected $especialidadTable;
    protected $especialistaTable;
    protected $relacionaequipoespecialidadTable;
    protected $cargoTable;
    protected $oficinaTable;

    public function setControllerVars()
    {
        $this->datatable = $this->sm->get('Gestor\Service\DatatableService');

        $this->directorrelacionTable = $this->sm->get('Gestor\Model\DirectorRelacionTable');
        $this->ultimosdatosTable = $this->sm->get('Gestor\Model\UltimosDatosOficinaTable');
        $this->equipoTable = $this->sm->get('Gestor\Model\EquipoTable');
        $this->especialistaTable = $this->sm->get('Gestor\Model\EspecialistaTable');
        $this->especialidadTable = $this->sm->get('Gestor\Model\EspecialidadTable');
        $this->lenguajeTable = $this->sm->get('Gestor\Model\LenguajeTable');
        $this->relacionaequipoespecialidadTable = $this->sm->get('Gestor\Model\RelacionaEquipoEspecialidadTable');
        $this->cargoTable = $this->sm->get('Gestor\Model\CargoTable');
        $this->oficinaTable = $this->sm->get('Gestor\Model\OficinaTable');

        $this->formService      = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->ultimosdatosTable);

    }

    public function printPdfAction()
    {
        $pdfService = $this->getServiceLocator()->get('PdfService');

        $pdfService->pdf1mas9($_SESSION['pdf_data']);

        die;
    }

    public function createAction()
    {
        //  En este caso la pantalla siguiente a la selección de datos la ponemos aparte, para no sobrecargar más el
        // código de la acción de index.

        // 1. Sacamos los datos del equipo, que son los únicos que no nos vienen explícitos
        $idEquipo = $_REQUEST['idequipo']; // Llora lo que quieras Zend, que voy a usar $_REQUEST
        $equipo = $this->equipoTable->getEquipo($idEquipo);

        $arrayLenguajes = $this->lenguajeTable->fetchAll()->toArray();

        // Vamos a sacar los nombres de todas las especialidades  // OJO!!!!!
        $arrayEspecialidades = $this->especialidadTable->fetchAllConIdioma(); // Por el campo "orden" ASC
        // Ahora para todas ellas
        foreach ($arrayEspecialidades as $i => $especialidad) :
            // Guardamos en un array su nombre
            $idEspecialidad = $especialidad->id;
            //$arrayNombresEspecialidad[$idEspecialidad] = $especialidad->id;
            $arrayNombresEspecialidad[$idEspecialidad]['nombreInterno'] = $especialidad->nombreInterno;
            $arrayNombresEspecialidad[$idEspecialidad]['id'] = $especialidad->id;

            // Campo ['nombreX'] por cada idioma de la tabla de lenguajes
            foreach($arrayLenguajes as $unLenguaje)
            {
                $nombreCopiar = 'nombre'.$unLenguaje['id'];
                $arrayNombresEspecialidad[$idEspecialidad][$unLenguaje['id']] = $especialidad->$nombreCopiar;
            }

            // Tenemos que sacar todos los especialistas de cada cosa también, pera hacer la combo
            // Para cada especialidad, sacamos los especialistas disponibles
            $espArray = $this->especialistaTable->fetchFromEspecialidad($idEspecialidad);
        endforeach;


        // Ahora vamos a sacar los datos de cada uno de los especialistas de este equipo
        $iddirectorrelacion = $equipo->iddirectorrelacion; // Para poder usarlo luego
        $arrayEspecialistas['id'] = $equipo->id;

        foreach($arrayNombresEspecialidad as $j => $especialidadParticular)
        {
            // El $j tiene el idEspecialidad
            $idEspecialidad = $j;
            // Ahora, sacamos quien es el especialista que está en este equipo y en esta especialidad
            $datosEspecialista = $this->relacionaequipoespecialidadTable->fetchFromEspecialidadEquipo($idEspecialidad, $equipo->id);
            // Los cargamos en la especialidad especifica
            $arrayEspecialistas[$idEspecialidad]['nombre'] = $datosEspecialista->nombre;
            $arrayEspecialistas[$idEspecialidad]['apellido1'] = $datosEspecialista->apellido1;
            $arrayEspecialistas[$idEspecialidad]['apellido2'] = $datosEspecialista->apellido2;
            $arrayEspecialistas[$idEspecialidad]['foto'] = $datosEspecialista->foto;
            $arrayEspecialistas[$idEspecialidad]['idespecialista'] = $datosEspecialista->idEspecialista;
            //id_especialista
        }

        // Este chorrazo para la info de los equipos. TODO En un mundo ideal habría que sacarlo a los Model y dejar de repetirlo

        // En el array del $_REQUEST (que será $datosFormulario) vamos a cuadrar los info de los cargos
        $datosFormulario = $_REQUEST;

        // Sacamos los datos de oficina
        $datosOficinaTmp = $this->oficinaTable->getOficinaPorDirector($iddirectorrelacion);

        // Te lo pongo más fácil y va a $datosFormulario
        $datosFormulario['oficinaDireccion']    = $datosOficinaTmp->direccion;
        $datosFormulario['oficinaLocalidad']    = $datosOficinaTmp->localidad;
        $datosFormulario['oficinaCp']           = $datosOficinaTmp->cp;
        $datosFormulario['oficinaNumero']       = $datosOficinaTmp->numerooficina;
        $datosFormulario['oficinaNombre']       = $datosOficinaTmp->nombre;
        $datosFormulario['oficinaProvincia']    = $datosOficinaTmp->nombreProvincia;

        // Sacamos los datos del director de relacion
        $encryptKey = $this->config['encrypt_key'];
        $datosDirector = $this->directorrelacionTable->getDirectorRelacion($iddirectorrelacion, $encryptKey);
        $datosFormulario['directorrelacionNombre'] = $datosDirector->nombre;
        $datosFormulario['directorrelacionApellido1'] = $datosDirector->apellido1;
        $datosFormulario['directorrelacionApellido2'] = $datosDirector->apellido2;
        $datosFormulario['directorrelacionMovil'] = $datosDirector->movil;
        $datosFormulario['directorrelacionEmail'] = $datosDirector->emailDecrypted;
        $datosFormulario['directorrelacionTrato'] = $datosDirector->trato;

        // TODO AHORA HAY QUE MONTAR EL PDF. ********** MIGUEL AQUI TE EXPLICO LOS TRES ARRAYS **********

        $this->parseDatos($datosFormulario);

        $_SESSION['pdf_data'] = array(
            'especialistas'     => $arrayEspecialistas,
            'especialidades'    => $arrayNombresEspecialidad,
            'datos_form'        => $datosFormulario
        );

        if ((bool)$datosFormulario['generaroficina']) {
            $this->ultimosdatosTable->saveUltimosDatos($equipo->iddirectorrelacion, $datosFormulario);
        }

        die;
    }

    private function parseDatos(&$datosFormulario)
    {
        //Generamos un array que sea más sencillo de recorrer en la vista. También nos ayudará a guardar
        //los datos más facilmente en la tabla ultimos datos oficina

        $generarJson = array(
            'gc' => 'gestion_comercial',
            'ga' => 'gestion_administrativa',
            'ot' => 'otros_contactos'
        );

        foreach ($generarJson as $prefijo => $tipoDato) {
            if (isset($datosFormulario[$tipoDato])) {
                foreach ($datosFormulario[$tipoDato] as $campo => $datos) {
                    if (!is_numeric($campo)) {
                        $datosFormulario[$tipoDato] = array(
                            array($datos)
                        );
                    }
                }
            } else {
                $datosFormulario[$tipoDato] = array();
            }
        }
    }

    public function impedirAction()
    {
        return new ViewModel(array(

        ));
    }

    public function impedirnoequiposAction()
    {
        return new ViewModel(array(

        ));
    }

    public function indexAction()
    {

        // 1. Sacamos el id de perfil del usuario
        $userData = $this->getUserData();
        $idPerfil = $userData->idPerfil;

        switch ($idPerfil)
        {
            case 1: // Superadministrador
            case 2: // Coordinador
            case 4: // Administrador
                return $this->goToSection('presentacion', array(
                'action'  => 'impedir'
                ));
                break;
        }

        // Vamos a generar el formulario primero
        $presentacionForm = new PresentacionForm();
        $presentacionForm->setServiceLocator($this->getServiceLocator());
        $presentacionForm->addFields();

        $this->formService->setForm($presentacionForm);

        $form = $this->formService->getForm();

        // Vamos a sacar los cargos
        $cargosArray = $this->cargoTable->fetchAll()->toArray();

        // Sacamos los equipos que están relacionados con este director de relacion
        $userId = $this->getUserData()->id;
        $arrayEquipos = $this->equipoTable->fetchFromUserId($userId);
        $cuantosEquipos = $arrayEquipos->count();

        if ($cuantosEquipos == 0)
        {
            return $this->goToSection('presentacion', array(
                'action'  => 'impedirnoequipos'
            ));
        }


        // Vamos a sacar los nombres de todas las especialidades
        $arrayEspecialidades = $this->especialidadTable->fetchAll(); // Por el campo "orden" ASC
        // Ahora para todas ellas
        foreach ($arrayEspecialidades as $i => $especialidad) :
            // Guardamos en un array su nombre
            $idEspecialidad = $especialidad->id;
            //$arrayNombresEspecialidad[$idEspecialidad] = $especialidad->id;
            $arrayNombresEspecialidad[$idEspecialidad]['nombreInterno'] = $especialidad->nombreInterno;
            $arrayNombresEspecialidad[$idEspecialidad]['id'] = $especialidad->id;

            // Tenemos que sacar todos los especialistas de cada cosa también, pera hacer la combo
            // Para cada especialidad, sacamos los especialistas disponibles
            $espArray = $this->especialistaTable->fetchFromEspecialidad($idEspecialidad);

            foreach ($espArray as $n => $especialista) :
                $arrayEspecialistas[$idEspecialidad][$n] = $especialista;
            endforeach;
        endforeach;

        // Ahora vamos a sacar los datos de cada uno de los especialistas de este equipo
        $contador = 0;
        foreach($arrayEquipos as $i => $equipo) {
            $iddirectorrelacion = $equipo->iddirectorrelacion; // Para poder usarlo luego
            $arrayFinal[$contador]['id'] = $equipo->id;

            foreach($arrayNombresEspecialidad as $j => $especialidadParticular)
            {
                // El $j tiene el idEspecialidad
                $idEspecialidad = $j;
                // Ahora, sacamos quien es el especialista que está en este equipo y en esta especialidad
                $datosEspecialista = $this->relacionaequipoespecialidadTable->fetchFromEspecialidadEquipo($idEspecialidad, $equipo->id);
                // Los cargamos en la especialidad especifica
                $arrayFinal[$contador][$idEspecialidad]['nombre'] = $datosEspecialista->nombre;
                $arrayFinal[$contador][$idEspecialidad]['apellido1'] = $datosEspecialista->apellido1;
                $arrayFinal[$contador][$idEspecialidad]['apellido2'] = $datosEspecialista->apellido2;
                $arrayFinal[$contador][$idEspecialidad]['foto'] = $datosEspecialista->foto;
                $arrayFinal[$contador][$idEspecialidad]['idespecialista'] = $datosEspecialista->idEspecialista;
                //id_especialista

            }
            $contador++;
        } // Este chorrazo para la info de los equipos. TODO En un mundo ideal habría que sacarlo a los Model y dejar de repetirlo



        // Ahora sacamos, si lo hay, el "ultimos_datos_oficina"
        $ultimosDatos = $this->ultimosdatosTable->fetchLatest($iddirectorrelacion);

        // Cambiamos datos del form si no está vacío
        //if ($ultimosDatos != 0) //Da un pete(notice). Comprobamos que ultimosDatos sea un objecto
        if (is_object($ultimosDatos))
        {
            // Le pasamos los datos del bloque director
            $form->get('do_nombre')->setValue($ultimosDatos->doNombre);
            $form->get('do_telefono')->setValue($ultimosDatos->doTelefono);
            $form->get('do_email')->setValue($ultimosDatos->doEmail);
            // Le pasamos los datos del bloque gestor
            $form->get('ge_nombre')->setValue($ultimosDatos->geNombre);
            $form->get('ge_telefono')->setValue($ultimosDatos->geTelefono);
            $form->get('ge_email')->setValue($ultimosDatos->geEmail);
            // Le pasamos los datos de telefonos
            //$form->get('telefonos')->setValue($ultimosDatos->telefonos);
            // (no le pasamos lo que hay en el json pq eso no va al Form sino que lo desplegamos en la vista)
        }




        // Por ultimos sacamos los lenguajes para los checkbox
        $arrayLenguajes = $this->lenguajeTable->fetchAll()->toArray();


        return new ViewModel(array(
            'form'        => $form,
            'arrayEquipos' => $arrayFinal,
            'cuantosEquipos' => $cuantosEquipos,
            'arrayEspecialistas' => $arrayEspecialistas,
            'arrayNombresEspecialidad' => $arrayNombresEspecialidad,
            'ultimosDatos' => $ultimosDatos,
            'cargosArray' => $cargosArray,
            'arrayLenguajes' => $arrayLenguajes,
        ));

    }
}