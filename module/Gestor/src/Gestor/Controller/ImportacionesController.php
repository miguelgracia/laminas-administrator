<?php
namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;
use Gestor\Form\OficinaForm;



class ImportacionesController extends AuthController implements ControllerInterface
{
    protected $oficinaTable;
    protected $directorRelacionTable;
    protected $especialistaTable;
    protected $gestorUsuariosTable;
    protected $equipoTable;
    protected $relacionaEquipoEspecialidadTable;

    public function setControllerVars()
    {
        $this->oficinaTable = $this->sm->get('Gestor\Model\OficinaTable');
        $this->cargoTable = $this->sm->get('Gestor\Model\CargoTable');
        $this->directorRelacionTable = $this->sm->get('Gestor\Model\DirectorRelacionTable');
        $this->especialistaTable = $this->sm->get('Gestor\Model\EspecialistaTable');
        $this->direccionRegionalTable = $this->sm->get('Gestor\Model\DireccionRegionalTable');
        $this->gestorUsuariosTable = $this->sm->get('Gestor\Model\GestorUsuariosTable');
        $this->equipoTable = $this->sm->get('Gestor\Model\EquipoTable');
        $this->relacionaEquipoEspecialidadTable = $this->sm->get('Gestor\Model\RelacionaEquipoEspecialidadTable');

        $this->datatable = $this->sm->get('Gestor\Service\DatatableService');
        $this->formService = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->oficinaTable);
    }





    // -------------------------------------- 5. Importación de equipos -----------------------------------
    private function ImportarEquipos($arrayFichero, $arrayDirectores, $arrayEspecialistas)
    {
        // Tenemos un listado de todas las líneas del fichero que indican en cada una de ellas
        //
        // - En $arrayDirectores tenemos N entradas
        // - En $arrayEspecialistas tenemos (N*9) entradas
        //
        //  Así que solo tenemos que hacer N inserciones de equipos, metiendo en orden cada uno de los
        // especialistas.
        //
//var_dump($arrayDirectores);
//var_dump($arrayEspecialistas); die;
        $contadorEspecialistas = 0;
        for ($i = 0; $i < count($arrayDirectores); $i++)
        {
            // 1. Comprobamos si existe este equipo para el director
            $hayRow = $this->equipoTable->getEquipoPorDirectorRelacion($arrayDirectores[$i]['id']);
            if ($hayRow == false)
            {
                // Lo creamos y sacamos su id
                $idEquipo = $this->equipoTable->CrearEquipo($arrayDirectores[$i]['id']);
            } else {
                // Sacamos su id
                $idEquipo = $hayRow->id;
            }

            // Una vez tenemos el equipo ya sea creado o porque estaba...
            for ($j = 0; $j < 9; $j++)
            {

//echo ("Buscando relacion ".$idEquipo." con ".$arrayEspecialistas[$contadorEspecialistas]['id']."<br/>");
                // 3. Comprobamos si existe la combinacion de $idEquipo con $arrayEspecialistas[$contadorEspecialistas]['id']
                $hayRow2 = $this->relacionaEquipoEspecialidadTable->getPorRelacion($idEquipo, $arrayEspecialistas[$contadorEspecialistas]['id']);
                // 4. Si no existe, lo metemos
                if ($hayRow2 == false) {
//echo ("NO -> Write<br/>");
                    $this->relacionaEquipoEspecialidadTable->Crear($idEquipo, $arrayEspecialistas[$contadorEspecialistas]['id']);
                }

                $contadorEspecialistas++;
            }

        }
//die;
    }




    // -------------------------------------- 5. Importación de especialistas -----------------------------------
    private function PartirNombre($campo)
    {
        //  Para hacer algunas excepciones, vamos a trapichear algunos nombres poniendo _ para unir trozos, y una
        // vez hecho el proceso lo quitamos
        if ($campo == "MARIA DOLORES BARBEITO RAMIREZ") { $campo = "MARIA_DOLORES BARBEITO RAMIREZ"; }
        if ($campo == "Miguel Angel Torres Vega") { $campo = "Miguel_Angel Torres Vega"; }
        if ($campo == "Carlos Alberto Flores") { $campo = "Carlos_Alberto Flores"; }
        if ($campo == "Jose Ignacio Miranda Gomez") { $campo = "Jose_Ignacio Miranda Gomez"; }
        if ($campo == "Josep Mª de la Rocha") { $campo = "Josep_Mª de_la_Rocha"; }
        if ($campo == "MARIA TERESA GELI GIRONES") { $campo = "MARIA_TERESA GELI GIRONES"; }
        if ($campo == "Samantha del Rincon Sanchez") { $campo = "Samantha del_Rincon Sanchez"; }
        if ($campo == "MAITE DE ANDRÉS SANTAMARINA") { $campo = "MAITE DE_ANDRÉS SANTAMARINA"; }
        if ($campo == "LUIS FRANCISCO VILLALBA LAHUERTA") { $campo = "LUIS_FRANCISCO VILLALBA LAHUERTA"; }
        if ($campo == "Eduardo de Saracho") { $campo = "Eduardo de_Saracho"; }
        if ($campo == "M  Angeles Lopez Huerta") { $campo = "M.Angeles Lopez Huerta"; } // OJO A ESTA
        if ($campo == "Jose Manuel Adelantado") { $campo = "Jose_Manuel Adelantado"; }
        if ($campo == "Miguel Angel García Ruiz") { $campo = "Miguel_Angel García Ruiz"; }
        if ($campo == "Francisco Jesús Ayllón Céspedes") { $campo = "Francisco_Jesús Ayllón Céspedes"; }
        if ($campo == "JOSÉ MARÍA MARTÍNEZ IBARRA") { $campo = "JOSÉ_MARÍA MARTÍNEZ IBARRA"; }
        if ($campo == "Maria José Moliner Ruiz") { $campo = "Maria_José Moliner Ruiz"; }
        if ($campo == "Manuel Ramon Santos Lacal") { $campo = "Manuel_Ramon Santos Lacal"; }
        if ($campo == "CARLOS MANUEL GUTIÉRREZ GUINEA") { $campo = "CARLOS_MANUEL GUTIÉRREZ GUINEA"; }
        if ($campo == "Juan Carlos Sanchez Vicente") { $campo = "Juan_Carlos Sanchez Vicente"; }
        if ($campo == "RAQUEL DE LA PARTE BUSTOS") { $campo = "RAQUEL DE_LA_PARTE BUSTOS"; }
        if ($campo == "Luis Fernando Guerrero Perez de la Blanca") { $campo = "Luis_Fernando Guerrero Perez de la Blanca"; }
        if ($campo == "ALEJANDRO DE LEMUS MARTIN") { $campo = "ALEJANDRO DE_LEMUS MARTIN"; }
        if ($campo == "María J. Barazar Herrera") { $campo = "María_J. Barazar Herrera"; }
        if ($campo == "JUAN FRANCISCO CARRIÓN PINA") { $campo = "JUAN_FRANCISCO CARRIÓN PINA"; }
        if ($campo == "LUIS ALBERTO BÁEZ MÁRQUEZ") { $campo = "LUIS_ALBERTO BÁEZ MÁRQUEZ"; }
        // Eran más de las que pensaba, mierda. Pero bueno, así queda todo perfecto

        // División básica en nombre y apellidos
        $array = preg_split('/ /', $campo);
        $arrayFinal['nombre'] = $array[0];
        $arrayFinal['apellido1'] = $array[1];
        $cadenanombre = '';
        for ($i = 2; $i < count($array); $i++)
        {
            $cadenanombre = $cadenanombre." ".$array[$i];
        }
        $arrayFinal['apellido2'] = trim($cadenanombre);

        // Ahora quitamos las _ de las excepciones que contemplamos
        $arrayFinal['nombre'] = str_replace('_', ' ', $arrayFinal['nombre']);
        $arrayFinal['apellido1'] = str_replace('_', ' ', $arrayFinal['apellido1']);
        $arrayFinal['apellido2'] = str_replace('_', ' ', $arrayFinal['apellido2']);

        return $arrayFinal;
    }

    private function ImportarEspecialistas($arrayFichero)
    {
        //  Esto tiene algunas particularidades:
        //
        //   * Primero, que tenemos que separar los campos de nombres y apellidos (porque nosotros lo guardamos por separado)
        //   * Segundo, hay que cuadrar a cada uno con su especialidad, esto lo haremos hardcodeando la columna
        $arrayEspecialistas = array();
        $contador = 0;
        for ($i = 0; $i < count($arrayFichero); $i++) {
            // El numero al lado de la variable campo está ordenado según lo metimos nosotros en la BBDD
            $arrayEspecialistas[$contador] = $this->PartirNombre(trim($arrayFichero[$i][12])); // a corto plazo
            $arrayEspecialistas[$contador]['idespecialidad'] = 1;
            $contador++;
            $arrayEspecialistas[$contador] = $this->PartirNombre(trim($arrayFichero[$i][13])); // a largo plazo
            $arrayEspecialistas[$contador]['idespecialidad'] = 2;
            $contador++;
            $arrayEspecialistas[$contador] = $this->PartirNombre(trim($arrayFichero[$i][14])); // comercio exterior
            $arrayEspecialistas[$contador]['idespecialidad'] = 3;
            $contador++;
            $arrayEspecialistas[$contador] = $this->PartirNombre(trim($arrayFichero[$i][15])); // renting => antiguo "tipos de interes"
            $arrayEspecialistas[$contador]['idespecialidad'] = 4;
            $contador++;
            $arrayEspecialistas[$contador] = $this->PartirNombre(trim($arrayFichero[$i][16])); // medios de pago
            $arrayEspecialistas[$contador]['idespecialidad'] = 5;
            $contador++;
            $arrayEspecialistas[$contador] = $this->PartirNombre(trim($arrayFichero[$i][17])); // seguros
            $arrayEspecialistas[$contador]['idespecialidad'] = 6;
            $contador++;
            $arrayEspecialistas[$contador] = $this->PartirNombre(trim($arrayFichero[$i][18])); // servicios transaccionales
            $arrayEspecialistas[$contador]['idespecialidad'] = 7;
            $contador++;
            $arrayEspecialistas[$contador] = $this->PartirNombre(trim($arrayFichero[$i][19])); // banca de inversion
            $arrayEspecialistas[$contador]['idespecialidad'] = 8;
            $contador++;
            $arrayEspecialistas[$contador] = $this->PartirNombre(trim($arrayFichero[$i][20])); // mercados => antiguo "mercado de divisas"
            $arrayEspecialistas[$contador]['idespecialidad'] = 9;
            $contador++;
        }

        // Podríamos meter los equipos ya, pero mejor tenerlo por separado
        for ($k = 0; $k < count($arrayEspecialistas); $k++)
        {
            $existente = $this->especialistaTable->getEspecialistaPorNombreCompleto($arrayEspecialistas[$k]); // Por nombre y apellidos
            if (($existente == null) || ($existente == ''))
            {
                $arrayDirectores[$k]['id'] = $this->especialistaTable->GrabarExcel($arrayEspecialistas[$k]);
            } else {
                $arrayEspecialistas[$k]['id'] = $existente[0]->id;
            }
        }

        return $arrayEspecialistas;
    }



    // -------------------------------------- 4. Importación de directores -----------------------------------
    private function ImportarDirectores($arrayFichero, $arrayCargos, $arrayDRs, $arrayOficinas)
    {
        $arrayDirectores = array();
        $encryptKey = $this->config['encrypt_key'];
        $clavePorDefecto = $this->config['clavePorDefecto'];
        $coordinadorStandard = $this->config['coordinadorStandard'];
        for ($i = 0; $i < count($arrayFichero); $i++) {
            // Vamos a meter los datos
            $arrayDirectores[$i]['nombre'] = trim($arrayFichero[$i][0]);
            $arrayDirectores[$i]['apellido1'] = trim($arrayFichero[$i][1]);
            $arrayDirectores[$i]['apellido2'] = trim($arrayFichero[$i][2]);
            $arrayDirectores[$i]['login'] = trim($arrayFichero[$i][3]);
            $arrayDirectores[$i]['email'] = trim($arrayFichero[$i][7]);
            $arrayDirectores[$i]['iddireccionregional'] = $arrayDRs[$i]['id']; // Va a coincidir con la anterior pasada
            $arrayDirectores[$i]['idcargo'] = $arrayCargos[$i]['id'];
            $arrayDirectores[$i]['idoficina'] = $arrayOficinas[$i]['id'];
            // Esto lo vamos metiendo a mano
            $arrayDirectores[$i]['password'] = $clavePorDefecto;
            $arrayDirectores[$i]['idPerfil'] = 3;
            $arrayDirectores[$i]['validado'] = 0;
            $arrayDirectores[$i]['activo'] = 1;
            $arrayDirectores[$i]['idcoordinador'] = $coordinadorStandard;
        }

        // Construido el array, vamos a insertar o a sacar sus IDs
        for ($k = 0; $k < count($arrayDirectores); $k++)
        {
            $existente = $this->directorRelacionTable->getDirectorRelacionPorEmail($arrayDirectores[$k]['email'], $encryptKey);
            if (($existente == null) || ($existente == ''))
            {
                // Entonces grabamos
                // Primero creamos la cuenta en el gestor de usuarios
                $arrayDirectores[$k]['idusuario'] =$this->gestorUsuariosTable->GrabarExcel($arrayDirectores[$k], $encryptKey, $this->config['clavePorDefecto']);
                // Una vez la tenemos, creamos la cuenta en la tabla de directores
                $arrayDirectores[$k]['id'] = $this->directorRelacionTable->GrabarExcel($arrayDirectores[$k], $encryptKey);

            } else {
                $arrayDirectores[$k]['id'] = $existente[0]->id;
                $arrayDirectores[$k]['idusuario'] = $existente[0]->idusuario;
            }
        }


        return $arrayDirectores;
    }


    // -------------------------------------- 3. Importación de oficinas -----------------------------------
    private function GetIdProvincia($provincia)
    {
        switch($provincia) {
            case 'LAS PALMAS': return 35; // 'Islas Canarias'
            case 'STA CRUZ DE' : return 61; // 'Tenerife'
            case 'BARCELONA' : return 8; // 'Barcelona'
            case 'GERONA' : return 17; // 'Gerona'
            case 'LERIDA' : return 25; // 'Lleida'
            case 'TARRAGONA' : return 43; // 'Tarragona'
            case 'ALBACETE' : return 2; // 'Albacete'
            case 'CIUDAD REAL' : return 13; // 'Ciudad Real'
            case 'CUENCA' : return 16; // 'Cuenca'
            case 'MADRID' : return 28; // 'Madrid'
            case 'TOLEDO' : return 45; // 'Toledo'
            case 'ALICANTE' : return 3; // 'Alicante'
            case 'BALEARES' : return 7; // 'Islas Baleares'
            case 'CASTELLON' : return 12; // 'Castellón'
            case 'MURCIA' : return 30; // 'Murcia'
            case 'VALENCIA' : return 46; // 'Valencia'
            case 'ASTURIAS' : return 33; // 'Asturias'
            case 'BURGOS' : return 9; // 'Burgos'
            case 'LA CORUNA' : return 15; // 'A Coruña'
            case 'LEON' : return 24; // 'León'
            case 'LUGO' : return 27; // 'Lugo'
            case 'ORENSE' : return 32; // 'Ourense'
            case 'PALENCIA' : return 34; // 'Palencia'
            case 'PONTEVEDRA' : return 36; // 'Pontevedra'
            case 'SALAMANCA' : return 37; // 'Salamanca'
            case 'SEGOVIA' : return 40; // 'Segovia'
            case 'VALLADOLID' : return 47; // 'Valladolid'
            case 'ARABA' : return 1; // 'Álava'
            case 'BIZKAIA' : return 48; // 'Vizcaya'
            case 'CANTABRIA' : return 39; // 'Cantabria'
            case 'GIPUZKOA' : return 20; // 'Guipúzcoa'
            case 'HUESCA' : return 22; // 'Huesca'
            case 'LA RIOJA' : return 26; // 'La Rioja'
            case 'NAVARRA' : return 31; // 'Navarra'
            case 'ZARAGOZA' : return 50; // 'Zaragoza'
            case 'ALMERIA' : return 4; // 'Almeria'
            case 'BADAJOZ' : return 6; // 'Badajoz'
            case 'CADIZ' : return 11; // 'Cádiz'
            case 'CEUTA' : return 62; // 'Ceuta'
            case 'CORDOBA' : return 14; // 'Córdoba'
            case 'GRANADA' : return 18; // 'Granada'
            case 'HUELVA' : return 21; // 'Huelva'
            case 'JAEN' : return 23; // 'Jaén'
            case 'MALAGA' : return 29; // 'Málaga'
            case 'SEVILLA' : return 41; // 'Sevilla'
            // Hasta aqui lo que hay en el Excel, pero faltan 7 que nos inventamos por si acaso con lo más razonable
            case 'AVILA' : return 5; // 'Avila'
            case 'CACERES' : return 10; // 'Cáceres'
            case 'GUADALAJARA' : return 19; // 'Guadalajara'
            case 'SORIA' : return 42; // 'Soria'
            case 'TERUEL' : return 44; // 'Teruel'
            case 'ZAMORA' : return 49; // 'Zamora'
            case 'MELILLA' : return 63; // 'Melilla'
            default : return 0; // Madrid por defecto
        }
    }

    private function ImportarOficinas($arrayFichero)
    {
        $arrayOficinas = array();
        for ($i = 0; $i < count($arrayFichero); $i++)
        {
            $arrayTemp['numerooficina'] = trim($arrayFichero[$i][6]); // El numero de oficina que nos hace de indice
            $arrayTemp['direccion'] = trim($arrayFichero[$i][8]); // Sacamos la direccion
            $arrayTemp['localidad'] = trim($arrayFichero[$i][9]); // Sacamos la localidad
            $arrayTemp['cp'] = trim($arrayFichero[$i][10]); // Sacamos el cp
            // Para la provincia vamos a necesitar un switch, así que lo sacamos a otro método
            $arrayTemp['idprovincia'] = $this->GetIdProvincia(trim($arrayFichero[$i][11]));
            $arrayOficinas[] = $arrayTemp;
        }

        // Construido el array de elementos únicos del Excel, vamos a insertarlas o a sacar sus IDs
        for ($k = 0; $k < count($arrayOficinas); $k++)
        {
            $existente = $this->oficinaTable->ObtenerPorNumero($arrayOficinas[$k]['numerooficina']);
            if (($existente == null) || ($existente == ''))
            {
                // Entonces grabamos
                $arrayOficinas[$k]['id'] = $this->oficinaTable->Grabar($arrayOficinas[$k]['numerooficina'], $arrayOficinas[$k]['direccion'],
                    $arrayOficinas[$k]['localidad'], $arrayOficinas[$k]['cp'], $arrayOficinas[$k]['idprovincia']);
            } else {
                $arrayOficinas[$k]['id'] = $existente[0]->id;
            }
        }

        return $arrayOficinas;
    }


    // -------------------------------------- 2. Importación de DRs -----------------------------------
    private function ImportarDRs($arrayFichero)
    {
        // Vamos a leerlos todos
        $arrayDRs = array();
        for ($i = 0; $i < count($arrayFichero); $i++)
        {
            $arrayDRs[]['nombre'] = trim($arrayFichero[$i][5]); // DRs - Aqui no hacemos el strtolower
        }

        for ($k = 0; $k < count($arrayDRs); $k++)
        {
            $existente = $this->direccionRegionalTable->ObtenerPorNombre($arrayDRs[$k]['nombre']);
            if (($existente == null) || ($existente == ''))
            {
                // Entonces grabamos
                $arrayDRs[$k]['id'] = $this->direccionRegionalTable->Grabar($arrayDRs[$k]['nombre']);
            } else {
                $arrayDRs[$k]['id'] = $existente[0]->id;
            }
        }

        // Ahora devolvemos un arrayCargos[0..n] que contiene como campos "id" y "nombre"
        return ($arrayDRs);
    }


    // -------------------------------------- 1. Importación de cargos -----------------------------------
    private function ImportarCargos($arrayFichero)
    {
        // Proceso
        $arrayCargos = array();
        for ($i = 0; $i < count($arrayFichero); $i++)
        {
            $arrayCargos[]['nombre'] = ucfirst(strtolower(trim($arrayFichero[$i][4])));
        }

        //  Para grabar, primero va a comprobar que no exista el cargo en sí. En un caso u otro,
        // guardamos el id que le hace referencia, porque luego lo necesitaremos para el alta de directores

        // **** Esto lo hacemos porque si vamos a meter Excel adicionales, no queremos repeticiones de cargos
        for ($k = 0; $k < count($arrayCargos); $k++)
        {
            $existente = $this->cargoTable->ObtenerPorNombre($arrayCargos[$k]['nombre']);
            if (($existente == null) || ($existente == ''))
            {
                // Entonces grabamos
                $arrayCargos[$k]['id'] = $this->cargoTable->Grabar($arrayCargos[$k]['nombre']);
            } else {
                $arrayCargos[$k]['id'] = $existente[0]->id;
            }
        }

        // Ahora devolvemos un arrayCargos[0..n] que contiene como campos "id" y "nombre"
        return ($arrayCargos);
    }




    // ***************************************************************************************************
    //                                        ACCION DE IMPORTACION
    // ***************************************************************************************************

    public function importAction()
    {

        $error = '';
        if (($_FILES['fichero']['tmp_name'] != null) && ($_FILES['fichero']['tmp_name'] != '')) {
            $handle = fopen($_FILES['fichero']['tmp_name'], "rw");
            if ($handle != null) {

                // Si llegamos hasta aquí es que vamos bien. Podemos empezar a procesarlo.
                $row = 0;
                while (($linea = fgets($handle)) !== false) { // Vamos a leerlo entero de primeras
                    // Vamos a trocearla
                    $arrayLinea = explode(';', $linea);
                    $arrayFichero[$row] = $arrayLinea;
                    $row++;
                }
                fclose($handle);

                // OJO TRIM A TODOS LOS CAMPOS

                //  Ahora que lo tenemos en un bonito array, empezamos a meter las cosas que contempla. Lo he
                // pasado primero a un array porque tendremos que pegarle varias pasadas.

                // 1. Montamos la tabla de cargos con los contenidos de la columna 4
                $arrayCargos = $this->ImportarCargos($arrayFichero); // Nos devolverá un array de cargos e IDs

                // 2. Montamos la tabla de DR con los contenidos de la columna 5
                $arrayDRs = $this->ImportarDRs($arrayFichero);

                // 3. Montamos la tabla de Oficina con los contenidos de las columnas 6, 8, 9, 10 y 11.
                //  La provincia va matcheada a mano
                $arrayOficinas = $this->ImportarOficinas($arrayFichero);

                // 4. Cargamos los directores de relación con sus cargos, oficina y DR
                //  (tenemos que meterlo tanto en director_relacion como en gestorusuario, y enlazarlos)
                $arrayDirectores = $this->ImportarDirectores($arrayFichero, $arrayCargos, $arrayDRs, $arrayOficinas);

                // 5. Hacemos una carga de todos los especialistas
                $arrayEspecialistas = $this->ImportarEspecialistas($arrayFichero);

                // 6. Hacemos la carga de los equipos para los DR
                $arrayEquipos = $this->ImportarEquipos($arrayFichero, $arrayDirectores, $arrayEspecialistas);

//var_dump($arrayCargos);
//var_dump($arrayDRs);
//var_dump($arrayOficinas);
//var_dump($arrayDirectores);
//var_dump($arrayEspecialistas);
//die;
                return new ViewModel(array(
                    'exito' => 'si',
                    'numLineas' => count($arrayCargos), // Da igual, sacamos el numero de líneas de cualquier array
                ));




            } else {
                $error = ("Error al subir el fichero");
                die;
            }
        } else {
            $error = ("No se subió ningún fichero");
        }

        return new ViewModel(array(
            'error' => $error,
        ));

    }









    private function ImportarFotos($arrayFichero, $path)
    {

        for($i = 0; $i < count($arrayFichero); $i++)
        {
            $nombreTotal = trim($arrayFichero[$i][0]);
            $filename = trim($arrayFichero[$i][1]);

//echo ("Procesando ".$nombreTotal." con fichero ".$filename."<br/>");

            // 1. Check de si existe el nombre entre los especialistas
            $arrayNombre = $this->PartirNombre($nombreTotal);
            $arrayResult = $this->especialistaTable->getEspecialistaPorNombreCompleto($arrayNombre);


           if ($arrayResult == null)
            {
                $this->errores .= "Nombre no encontrado en línea ".($i+1).", '".$nombreTotal."' <br/>";
//echo ("Nombre no encontrado en línea ".($i+1));
            } else {
//echo ("[NOMBRE CORRECTO]");

               // 2. Check de si existe el fichero como .jpg
               //  no nos los pueden pasar sin extensión

               $dondeFichero = $path.'/'.$filename.'.jpg';
               if (file_exists($dondeFichero))
               {

                   // 3. Si va bien, generamos un nombre random y subimos el fichero con el Service de ImageUpload
                   $arrayFiles['type'] = 'image/jpeg'; // Vamos a montar esto
                   $arrayFiles['tmp_name'] = $dondeFichero;

                   // Ahora vamos a crear nosotros el avatar
                   $config = $this->sm->get('Config');
                   $pathUpload = $config['pathbase_upload'].$config['path_especialista'];

                   // Llamamos al servicio que nos va a grabar el fichero y a montar el thumbnail
                   $nombreNuevoFichero = $this->sm->get('Gestor\Service\ImageUploadService')->UploadImage($pathUpload,
                       $config['maxWidthEspecialista'], $config['maxHeightEspecialista'],
                       $arrayFiles, $arrayResult[0]->id);

                   // Borramos el fichero anterior
                   if ($nombreNuevoFichero != -1) {
                       // Si había una foto antigua para este especialista, tenemos que borrarla!
                       //$especialistaOld = $this->especialistaTable->getEspecialista($arrayResult[0]->id);
                       //$fotoBorrar = $especialistaOld->foto;
                       $fotoBorrar = ($arrayResult[0]->foto);

                       // Guardamos en la bbdd el nombre de la foto
                       $this->especialistaTable->saveFoto($arrayResult[0]->id, $nombreNuevoFichero);

                       // Vamos a borrar la anterior, si la hay
                       if (($arrayResult[0]->foto != '') && ($arrayResult[0]->foto != null))
                       {
                           $nombreBorrar = $pathUpload.$fotoBorrar; //$especialista->foto;
                           $nombreBorrarThumb = substr($nombreBorrar, 0, strlen($nombreBorrar) - 4)."_thumb".substr($nombreBorrar, (strlen($nombreBorrar) - 4), strlen($nombreBorrar));
                           unlink($nombreBorrar);
                           unlink($nombreBorrarThumb);
                       }
                   }

               } else {
                   $this->errores .= "Fichero jpg no encontrado en línea ".($i+1).", '".$nombreTotal."' <br/>";
               }
           }
        }
    }



    // ***************************************************************************************************
    //                                        ACCION DE IMPORTACION... DE FOTOS
    // ***************************************************************************************************

    public function importFotosAction()
    {

        // 1. Sacamos el Excel que se ha subido
        $error = '';
        if (($_FILES['fichero']['tmp_name'] != null) && ($_FILES['fichero']['tmp_name'] != '')) {
            $handle = fopen($_FILES['fichero']['tmp_name'], "rw");
            if ($handle != null) {

                // Si llegamos hasta aquí es que vamos bien. Podemos empezar a procesarlo.
                $row = 0;
                while (($linea = fgets($handle)) !== false) { // Vamos a leerlo entero de primeras
                    // Vamos a trocearla
                    $arrayLinea = explode(';', $linea);
                    $arrayFichero[$row] = $arrayLinea;
                    $row++;
                }
                fclose($handle);

                $this->ImportarFotos($arrayFichero, $_REQUEST['rutaImagenes']);

                return new ViewModel(array(
                    'exito' => 'si',
                    'errores' => $this->errores,
                    'numLineas' => count($arrayFichero), // Da igual, sacamos el numero de líneas de cualquier array
                ));




            } else {
                $error = ("Error al subir el fichero");
                die;
            }
        } else {
            $error = ("No se subió ningún fichero");
        }

        return new ViewModel(array(
            'error' => $error,
        ));

    }




    public function indexAction()
    {
        return new ViewModel(array(
        ));
    }

}
