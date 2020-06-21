<?php

namespace Api\Service;

use Api\Model\AccessoryLocaleTable;
use Api\Model\AccessoryTable;
use Zend\Db\Sql\Predicate\Expression;

class AccessoryService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = AccessoryTable::class;
    protected $tableLocaleName = AccessoryLocaleTable::class;

    public function getAccessories($lang, $isFeatured = null, $page = 0)
    {
        $this->table->setTableLocaleService($this->tableLocale);

        $closureFunction = function (&$select, &$where) use ($lang, $isFeatured, $page) {
            $select->join(
                'accessory_categories',
                new Expression('accessory_categories.id =' . 'accessories.accessory_categories_id'),
                [
                    'category_key' => 'key'
                ]
            )->join(
                'accessory_categories_locales',
                new Expression('accessory_categories_locales.related_table_id = accessory_categories.id AND languages.id = accessory_categories_locales.language_id'),
                [
                    'category_title' => 'title',
                    'category_url_key' => 'url_key',
                ]
            )->order('accessories.created_at DESC');

            $where['accessory_categories.active'] = '1';

            if (!is_null($isFeatured)) {
                $where['accessories.show_in_home'] = (string) $isFeatured;

                if (!$isFeatured) {
                    $select->offset(($page * 3))->limit(3);
                }
            }

        };;

        $tableFields = [
            'key' => 'key',
            'image_url' => 'image_url',
            'show_in_home' => 'show_in_home'
        ];

        $localeTableFields = [
            'title' => 'title',
            'url_key' => 'url_key',
            'content' => 'content'
        ];

        return $this->table->allWithLocale($lang, $tableFields, $localeTableFields, $closureFunction);
    }
}
