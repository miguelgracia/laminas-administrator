<?php

namespace Gestor\Service;

use Zend\Filter\Word\SeparatorToCamelCase;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

class PdfService implements FactoryInterface
{
    protected $serviceLocator;
    protected $request;

    protected $viewRenderer;
    protected $viewModel;

    protected $pdfData = array();

    protected $mPdf;
    protected $html;
    protected $output;

    protected $config;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return $this
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $this->request        = $this->serviceLocator->get('Request');
        $this->viewRenderer   = $this->serviceLocator->get('ViewRenderer');
        $this->viewModel      = new ViewModel();

        $this->config         = $this->serviceLocator->get('Config');
        $this->setPdfTemplate();

        $this->mPdf           = new \mPDF('', 'A4-L',0,0,0,0,0,0,0,0);

        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setPdfTemplate($template = 'gestor/presentacion/pdf/template')
    {
        $this->viewModel->setTemplate($template);
    }

    public function addPdfData($data = array())
    {
        $this->pdfData += $data;
    }

    public function setHtml()
    {
        $this->viewModel->setVariables($this->pdfData);
        $this->html = $this->viewRenderer->render($this->viewModel);
    }

    public function render($filename, $forceDownload = false)
    {
        $this->mPdf->WriteHTML($this->html);

        if ($forceDownload) {
            $this->mPdf->Output($filename.'.pdf', 'D');
        } else {
            $this->mPdf->Output();
        }
    }

    public function pdf1mas9($pdfData = array())
    {
        // $services = $this->serviceLocator->getRegisteredServices();
        $params = $this->serviceLocator->get('controllerpluginmanager')->get('Params');

        $langId = $params->fromRoute('id');

        $data = $pdfData;

        if (isset($data['especialistas']['id'])) {
            unset($data['especialistas']['id']);
        }
        foreach ($data['especialistas'] as &$especialista) {
            $foto = $especialista['foto'];
            $foto = preg_replace("/(.*)\.(png|gif|jpg|jpeg)$/", "$1_thumb.$2", $foto);
            $especialista['foto'] = $this->config['base_url'] . '/upload/especialista/' . $foto;
        }

        $data['langId'] = $langId;
        $data['baseUrl'] = $this->config['base_url'];


        $data['str'] = $this->pdf1mas9Traducciones();

        $this->parsePaginaInfo($data, $langId);

        $this->addPdfData($data);

        $this->setHtml();

        $separator = new SeparatorToCamelCase(' ');

        $nombreEmpresa = $separator->filter(trim(ucfirst(strtolower($data['datos_form']['nombre_empresa']))));

        $this->render('Equipo1mas9' . $nombreEmpresa . $data['str']['codigo_idioma'][$langId], true);
    }

    /**
     * Las siguientes funciones son específicas para el pdf de 1mas9.
     * No es lo suyo que estén aquí pero por ahora es lo más ràpido.
     */

    private function parsePaginaInfo(&$dataInfo, $langId)
    {
        $datosForm = $dataInfo['datos_form'];

        $pageInfoView = new ViewModel();

        $str = $this->pdf1mas9Traducciones();

        $pageData = array();

        //la primera tab va a ser siempre el director de relacion
        $pageData[] = array(
            'titulo' => $str['pagina_4_director_relacion'][$langId],
            'data' => array(
                array(
                    'nombre' => $datosForm['directorrelacionNombre'] . $datosForm['directorrelacionApellido1'] . $datosForm['directorrelacionApellido2'],
                    'telefono' => $datosForm['directorrelacionMovil'],
                    'email' => $datosForm['directorrelacionEmail']
                )
            )
        );

        //Las siguientes tabs dependerán de si existe o no existe la información concreta

        if (isset($datosForm['gestor'])) {
            if (
                trim($datosForm['gestor']['ge_nombre']) != '' and
                trim($datosForm['gestor']['ge_telefono']) != '' and
                trim($datosForm['gestor']['ge_email']) != ''
            ) {
                $pageData[] = array(
                    'titulo' => $str['pagina_4_texto_gestor_negocio'][$langId],
                    'data' => array(
                        array(
                            'nombre' => $datosForm['gestor']['ge_nombre'],
                            'telefono' => $datosForm['gestor']['ge_telefono'],
                            'email' => $datosForm['gestor']['ge_email']
                        )
                    )
                );
            }
        }

        if(isset($datosForm['gestion_comercial']) and count($datosForm['gestion_comercial'])) {
            $data = array();
            foreach ($datosForm['gestion_comercial'] as $info) {
                $data[] = $info;
            }
            $pageData[] = array(
                'titulo' => $str['pagina_4_texto_gestion_comercial'][$langId],
                'data' => $data
            );
        }

        if(isset($datosForm['gestion_administrativa']) and count($datosForm['gestion_administrativa'])) {
            $data = array();
            foreach ($datosForm['gestion_administrativa'] as $info) {
                $data[] = $info;
            }
            $pageData[] = array(
                'titulo' => $str['pagina_4_texto_gestion_administrativa'][$langId],
                'data' => $data
            );
        }

        if(isset($datosForm['otros_contactos']) and count($datosForm['otros_contactos'])) {
            $data = array();
            foreach ($datosForm['otros_contactos'] as $info) {
                $data[] = $info;
            }
            $pageData[] = array(
                'titulo' => $str['pagina_4_texto_otros_contactos'][$langId],
                'data' => $data
            );
        }


        $pageDataCounter = count($pageData);
        $countRow = 1;

        foreach ($pageData as $tabIndex => &$tab) {

            $esPar =$tabIndex % 2 == 0;

            $tabTpl = 'gestor/presentacion/pdf/tab-info';

            if(($tabIndex + 1) == $pageDataCounter and $esPar) {
                $tabTpl = 'gestor/presentacion/pdf/tab-info-final';
            }

            if ($esPar) {
                $countRow = $countRow + 1;
            }

            $pageInfoView->setVariables(array(
                'titulo' => $tab['titulo'],
                'esPar' => $esPar,
                'countRow' => $countRow,
                'datos' => $tab['data'],
                'str' => $str,
                'baseUrl' => $this->config['base_url']
            ));

            $pageInfoView->setTemplate($tabTpl);

            $tab['html'] = $this->viewRenderer->render($pageInfoView);
        }

        $dataInfo['page_info_data'] = $pageData;
    }

    private function pdf1mas9Traducciones()
    {
        return array(
            'codigo_idioma' => array(
                '1' => 'ES',
                '2' => 'CAT',
                '3' => 'EN',
            ),
            'bloque_pagina_1_director_relacion' => array(
                '1' => '<span>Director</span><br/><span>de Relación</span>', //ESPAÑOL
                '2' => '<span>Director</span><br/><span>de Relació</span>', //CATALÁN
                '3' => '<span>Relations</span><br/><span>Director</span>',   //INGLÉS
            ),
            'bloque_pagina_1_especialistas' => array(
                '1' => 'Especialistas', //ESPAÑOL
                '2' => 'Especialistes', //CATALÁN
                '3' => 'Specialists',   //INGLÉS
            ),
            'bloque_pagina_1_expertos' => array(
                '1' => 'EXPERTOS EN EMPRESAS', //ESPAÑOL
                '2' => 'EXPERTS EN EMPRESES', //CATALÁN
                '3' => 'EXPERTS IN BUSINESSES',   //INGLÉS
            ),
            'especialista_en' => array(
                '1' => 'Especialista en', //ESPAÑOL
                '2' => 'Especialista en', //CATALÁN
                '3' => 'Specialist',      //INGLÉS
            ),
            'pagina_1_titular' => array(
                '1' => 'Le presentamos a su mejor equipo',
                '2' => 'Li presentem el seu millor equip',
                '3' => 'We’d like to introduce your best team',
            ),
            'pagina_1_texto_1' => array(
                '1' => 'En BBVA ayudamos a crecer a las empresas ofreciéndoles una atención personalizada. Por eso, cada
                uno de nuestros clientes tiene asignado su propio equipo de expertos que, liderados por su Director de
                Relación, le ayudan a tomar decisiones financieras acertadas para su empresa.',
                '2' => 'A BBVA ajudem a créixer les empreses oferint-los una atenció especialitzada. Per això, cada un dels
                nostres clients té assignat el seu equip d’experts, els quals, liderats pel seu Director de Relació, l’ajuden a
                prendre decisions financeres encertades per a la seva empresa.',
                '3' => 'BBVA helps businesses grow by providing them with personalised service. So each of our
                clients is assigned their own team of experts led by their Relations Director who help them make
                wise financial decisions for their company.'
            ),
            'pagina_1_texto_2' => array(
                '1' => 'Es el momento de conocer a todo su equipo.',
                '2' => 'És el moment que conegui tot el seu equip.',
                '3' => 'It’s time to meet your team.',
            ),
            'pagina_2_titular' => array(
                '1' => 'Este es su Equipo de Expertos 1+9',
                '2' => 'Aquest és el seu Equip d’Experts 1+9',
                '3' => 'This is your 1+9 Team of Experts',
            ),
            'pagina_2_pie' => array(
                '1' => 'Exclusivo para clientes de Banca de Empresas de BBVA con facturación anual superior a 5 millones de euros.',
                '2' => 'Exclusiu de Banca d’Empreses de BBVA per a clients amb facturació anual superior a 5 milions d’euros.',
                '3' => 'Exclusively for BBVA Business Banking clients with more than €5 million in annual turnover.',
            ),
            'pagina_2_texto_director_1' => array(
                '1' => 'DIRECTOR DE RELACIÓN',
                '2' => 'DIRECTOR DE RELACIÓ',
                '3' => 'RELATIONS DIRECTOR'
            ),
            'pagina_2_texto_director_0' => array(
                '1' => 'DIRECTORA DE RELACIÓN',
                '2' => 'DIRECTORA DE RELACIÓ',
                '3' => 'RELATIONS DIRECTOR'
            ),
            'pagina_3_titular' => array(
                '1' => 'Conozca en qué puede ayudarle su Equipo de Expertos 1+9',
                '2' => 'Conegui en què el pot ajudar el seu Equip d’Experts 1+9',
                '3' => 'Learn how your 1+9 Team of Experts can help you'
            ),
            'pagina_4_titular' => array(
                '1' => 'Estos son sus contactos para el día a día',
                '2' => 'Aquests són els seus contactes per al dia a dia',
                '3' => 'Here are your everyday contacts'
            ),
            'pagina_4_director_oficina' => array(
                '1' => 'Director de Oficina',
                '2' => 'Director d’Oficina',
                '3' => 'Branch Manager'
            ),
            'pagina_4_director_relacion' => array(
                '1' => 'Director de Relación',
                '2' => 'Director de Relació',
                '3' => 'Relations Director'
            ),
            'pagina_4_telefono' => array(
                '1' => 'Tlfo:',
                '2' => 'Tlfo:',
                '3' => 'Tlfo:'
            ),
            'pagina_4_texto_director' => array(
                '1' => 'Director de Relación',
                '2' => 'Director de Relació',
                '3' => 'Relations Director'
            ),
            'pagina_4_texto_gestor_negocio' => array(
                '1' => 'Gestor de Negocio transaccional',
                '2' => 'Gestor de Negoci transaccional',
                '3' => 'Transactional Business Manager'
            ),
            'pagina_4_texto_gestion_comercial' => array(
                '1' => 'Equipo de Gestión comercial',
                '2' => 'Equip de Gestió comercial',
                '3' => 'Sales management team'
            ),
            'pagina_4_texto_gestion_administrativa' => array(
                '1' => 'Equipo de Gestión administrativa',
                '2' => 'Equip de Gestió administrativa',
                '3' => 'Administrative management team'
            ),
            'pagina_4_texto_otros_contactos' => array(
                '1' => 'Otros contactos',
                '2' => 'Altres contactes',
                '3' => 'Other contacts'
            ),
        );
    }
}