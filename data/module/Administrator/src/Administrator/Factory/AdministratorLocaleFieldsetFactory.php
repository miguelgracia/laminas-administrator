<?php
namespace Administrator\Factory;

use Administrator\Service\AdministratorFormService;
use Administrator\Service\ConfigureFieldsetService;
use AmLanguage\Model\LanguageTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Metadata\Source\Factory;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdministratorLocaleFieldsetFactory implements FactoryInterface
{
    private $container;
    private $fieldsets = [];
    private $languages;

    protected $configureFieldsetService;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->container = $container;
        $this->languages = $container->get(LanguageTable::class)->all()->toKeyValueArray('id','name');
        $this->configureFieldsetService = $container->get(ConfigureFieldsetService::class);

        $formService = $container->get(AdministratorFormService::class);

        $baseFieldset = $formService->getBaseFieldset();

        $baseTableGateway = $baseFieldset->getTableGateway();
        $objectModel = $baseFieldset->getObjectModel();

        $primaryId = isset($objectModel->id) ? $objectModel->id : 0;

        $localeTableGatewayName = preg_replace(
            "/(Table)$/",
            "LocaleTable",
            get_class($baseTableGateway)
        );

        $localeTableGateway = $container->get($localeTableGatewayName);

        $localeModels = $localeTableGateway->findLocales($primaryId);

        foreach ($localeModels as $localModel) {
            $localModel->relatedTableId = $primaryId;

            $localeFieldset = $this->prepareFieldset($requestedName, $localModel);

            $this->fieldsets[$localeFieldset->getName()] = $localeFieldset;
        }

        return array_values($this->fieldsets);
    }

    private function prepareFieldset($fieldsetName, $objectModel)
    {
        $fieldset = (new $fieldsetName($fieldsetName));

        $metadata = Factory::createSourceFromAdapter($this->container->get('Zend\Db\Adapter\Adapter'));
        $tableGateway = $this->container->get($fieldset->getTableGatewayName());
        $columns = $metadata->getColumns($tableGateway->getTable());

        $fieldset
            ->setTableGateway($tableGateway)
            ->setColumns($columns)
            ->setObjectModel($objectModel)
            ->setName($fieldsetName . "\\" . $objectModel->languageId)
            ->setOption('is_locale',true)
            ->setOption('tab_name', $this->languages[$objectModel->languageId]);

        $this->configureFieldsetService->configure($fieldset);

        return $fieldset;
    }
}