<?php

namespace Application\Router\Http;

use Zend\Mvc\Router\Http\TranslatorAwareTreeRouteStack;

class LocaleTreeRouteStack extends TranslatorAwareTreeRouteStack
{
    protected $serviceLocator;

    public function init()
    {
        parent::init();

        $this->serviceLocator = $this->routePluginManager->getServiceLocator();
        
        $this->addRoutes(array(
            'home' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Home',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),
            'jobs' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/trabajos',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Job',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'category' => array(
                        'type'    => 'Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route'    => '/[:slug-category]',
                            'constraints' => array(
                                'slug-category' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller'    => 'Job',
                                'action'        => 'category',
                            ),
                        ),
                        'child_routes' => array(
                            'detail' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:slug-title]',
                                    'constraints' => array(
                                        'slug-title'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        '__NAMESPACE__' => 'Application\Controller',
                                        'controller'    => 'Job',
                                        'action'        => 'detail',
                                    ),
                                ),
                            ),
                        )
                    ),
                ),
            ),
            'blog' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/blog',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Blog',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'category' => array(
                        'type'    => 'Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route'    => '/[:slug-category]',
                            'constraints' => array(
                                'slug-category' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller'    => 'Blog',
                                'action'        => 'category',
                            ),
                        ),
                        'child_routes' => array(
                            'detail' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:slug-title]',
                                    'constraints' => array(
                                        'slug-title'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        '__NAMESPACE__' => 'Application\Controller',
                                        'controller'    => 'Blog',
                                        'action'        => 'detail',
                                    ),
                                ),
                            ),
                        )
                    ),
                ),
            ),
            'company' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/empresa',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Company',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'collaborators' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/colaboradores',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller'    => 'Company',
                                'action'        => 'collaborators',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                )
            ),
            'contact' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/contacto',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Contact',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),
            'legal' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/legales',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'coookies' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:legal-page]',
                            'constraints' => array(
                                'legal-page'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller'    => 'Legal',
                                'action'        => 'index',
                            ),
                        ),
                    ),
                )
            ),
        ));

    }
}