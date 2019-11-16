<?php
namespace Administrator\Factory;

use Interop\Container\ContainerInterface;
use Zend\Db\Metadata\Source\Factory;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdministratorLocaleFieldsetFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $languages = $container->get('AmLanguage\Model\LanguageTable')->all()->toKeyValueArray('id','name');

        $objectModel = $options['model'];
        $fieldset = (new $requestedName($requestedName));

        return $fieldset
            ->setServiceLocator($container)
            ->setTableGateway($container->get($fieldset->getTableGatewayName()))
            ->setObjectModel($objectModel)
            ->setMetadata(Factory::createSourceFromAdapter($container->get('Zend\Db\Adapter\Adapter')))
            ->setName($requestedName . "\\" . $objectModel->languageId)
            ->setOption('is_locale',true)
            ->setOption('tab_name', $languages[$objectModel->languageId]);
    }
}