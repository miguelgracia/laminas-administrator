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

    public function getDetail($lang, $accessoryUri)
    {
        $this->table->setTableLocaleService($this->tableLocale);
        $tableFields = [
            'id' => 'id',
            'accessory_categories_id' => 'accessory_categories_id',
            'key' => 'key',
            'image_url' => 'image_url',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deteled_at' => 'deleted_at',
            'active' => 'active'
        ];
        $localeFields = [
            'title' => 'title',
            'url_key' => 'url_key',
            'content' => 'content',
            'meta_description' => 'meta_description',
        ];
        return $this->table->findRow($lang, 'accessories_locales.url_key', $accessoryUri, $tableFields, $localeFields);
    }

    public function getFeaturedAccessories($lang)
    {
        $this->table->setTableLocaleService($this->tableLocale);

        $closureFunction = function (&$select, &$where) use ($lang) {
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
            $where['accessories.show_in_home'] = '1';
        };;

        $tableFields = [
            'key' => 'key',
            'image_url' => 'image_url',
        ];

        $localeTableFields = [
            'title' => 'title',
            'url_key' => 'url_key',
        ];

        return $this->table->allWithLocale($lang, $tableFields, $localeTableFields, $closureFunction);
    }

    public function getData($lang, $categoryFilter = false, $page = 1, $limit = 10)
    {
        $this->table->setTableLocaleService($this->tableLocale);

        $paginateCallback = function (&$select, &$where) use ($lang, $categoryFilter) {
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
                    'category_meta_description' => 'meta_description'
                ]
            )->order('accessories.created_at DESC');
            $where['accessory_categories.active'] = '1';

            if ($categoryFilter) {
                $where['accessory_categories_locales.url_key'] = $categoryFilter;
            }
        };;

        $tableFields = [
            'id' => 'id',
            'accessory_categories_id' => 'accessory_categories_id',
            'key' => 'key',
            'image_url' => 'image_url',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'active' => 'active',
            'deleted_at' => 'deleted_at'
        ];
        $localeTableFields = [
            'title' => 'title',
            'url_key' => 'url_key',
            'content' => 'content',
            'meta_description' => 'meta_description',
        ];

        $accessoriesPaginator = $this->table->paginate($lang, $tableFields, $localeTableFields, $paginateCallback);

        $accessoriesPaginator->setCurrentPageNumber($page);

        $accessoriesPaginator->setItemCountPerPage($limit);

        return $accessoriesPaginator;
    }
}
