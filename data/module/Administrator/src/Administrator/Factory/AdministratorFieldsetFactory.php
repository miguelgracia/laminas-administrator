<?php
namespace Administrator\Factory;

use Interop\Container\ContainerInterface;
use Zend\Db\Metadata\Source\Factory;
use Zend\Hydrator\ArraySerializable;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdministratorFieldsetFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $fieldset = (new $requestedName($requestedName));
        $isLocale = $options['is_locale'];

        $fieldset
            ->setServiceLocator($container)
            ->setHydrator(new ArraySerializable())
            ->setTableGateway($container->get($fieldset->getTableGatewayName()))
            ->setMetadata(Factory::createSourceFromAdapter($container->get('Zend\Db\Adapter\Adapter')));

        $fieldset->setObjectModel($options['model']);
        $fieldset->setOption('is_locale',$isLocale);

        if ($isLocale) {
            $objectModel = $fieldset->getObjectModel();
            $languages = $container->get('AmLanguage\Model\LanguageTable')->all()->toKeyValueArray('id','name');
            $fieldset->setName($requestedName . "\\" . $objectModel->languageId);
            $fieldset->setOption('tab_name', $languages[$objectModel->languageId]);
        }

        return $fieldset;
    }
}