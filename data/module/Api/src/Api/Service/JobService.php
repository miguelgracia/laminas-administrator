<?php

namespace Api\Service;

use Api\Model\JobLocaleTable;
use Api\Model\JobTable;
use Zend\Db\Sql\Predicate\Expression;

class JobService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = JobTable::class;
    protected $tableLocaleName = JobLocaleTable::class;

    public function getDetail($lang, $jobUri)
    {
        $this->table->setTableLocaleService($this->tableLocale);
        $tableFields = [
            'id' => 'id',
            'job_categories_id' => 'job_categories_id',
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
        return $this->table->findRow($lang, 'jobs_locales.url_key', $jobUri, $tableFields, $localeFields);
    }

    public function getFeaturedJobs($lang)
    {
        $this->table->setTableLocaleService($this->tableLocale);

        $closureFunction = function (&$select, &$where) use ($lang) {
            $select->join(
                'job_categories',
                new Expression('job_categories.id =' . 'jobs.job_categories_id'),
                [
                    'category_key' => 'key'
                ]
            )->join(
                'job_categories_locales',
                new Expression('job_categories_locales.related_table_id = job_categories.id AND languages.id = job_categories_locales.language_id'),
                [
                    'category_title' => 'title',
                    'category_url_key' => 'url_key',
                ]
            )->order('jobs.created_at DESC');

            $where['job_categories.active'] = '1';
            $where['jobs.show_in_home'] = '1';
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
                'job_categories',
                new Expression('job_categories.id =' . 'jobs.job_categories_id'),
                [
                    'category_key' => 'key'
                ]
            )->join(
                'job_categories_locales',
                new Expression('job_categories_locales.related_table_id = job_categories.id AND languages.id = job_categories_locales.language_id'),
                [
                    'category_title' => 'title',
                    'category_url_key' => 'url_key',
                    'category_meta_description' => 'meta_description'
                ]
            )->order('jobs.created_at DESC');
            $where['job_categories.active'] = '1';

            if ($categoryFilter) {
                $where['job_categories_locales.url_key'] = $categoryFilter;
            }
        };;

        $tableFields = [
            'id' => 'id',
            'job_categories_id' => 'job_categories_id',
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

        $jobsPaginator = $this->table->paginate($lang, $tableFields, $localeTableFields, $paginateCallback);

        $jobsPaginator->setCurrentPageNumber($page);

        $jobsPaginator->setItemCountPerPage($limit);

        return $jobsPaginator;
    }
}
