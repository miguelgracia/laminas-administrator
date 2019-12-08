<?php

namespace Api\Service;

use Api\Model\BlogLocaleTable;
use Api\Model\BlogTable;
use Zend\Db\Sql\Predicate\Expression;
use Zend\ServiceManager\Factory\FactoryInterface;

class BlogService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = BlogTable::class;
    protected $tableLocaleName = BlogLocaleTable::class;

    public function getDetail($lang, $blogUri)
    {
        $this->table->setTableLocaleService($this->tableLocale);
        $tableFields = [
            'id' => 'id',
            'blog_categories_id' => 'blog_categories_id',
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
        return $this->table->findRow($lang, 'blog_entries_locales.url_key', $blogUri, $tableFields, $localeFields);
    }

    public function getData($lang, $categoryFilter = false, $page = 1, $limit = 10)
    {
        $this->table->setTableLocaleService($this->tableLocale);

        $paginateCallback = function (&$select, &$where) use ($lang, $categoryFilter) {
            $select->join(
                'blog_categories',
                new Expression('blog_categories.id =' . 'blog_entries.blog_categories_id'),
                [
                    'category_key' => 'key'
                ]
            )->join(
                'blog_categories_locales',
                new Expression('blog_categories_locales.related_table_id = blog_categories.id AND languages.id = blog_categories_locales.language_id'),
                [
                    'category_title' => 'title',
                    'category_url_key' => 'url_key',
                    'category_meta_description' => 'meta_description'
                ]
            )->order('blog_entries.created_at DESC');
            $where['blog_categories.active'] = '1';

            if ($categoryFilter) {
                $where['blog_categories_locales.url_key'] = $categoryFilter;
            }
        };

        $tableFields = [
            'id' => 'id',
            'blog_categories_id' => 'blog_categories_id',
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

        $blogsPaginator = $this->table->paginate($lang, $tableFields, $localeTableFields, $paginateCallback);

        $blogsPaginator->setCurrentPageNumber($page);

        $blogsPaginator->setItemCountPerPage($limit);

        return $blogsPaginator;
    }
}
