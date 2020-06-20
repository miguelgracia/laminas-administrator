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

    public function getJobs($lang)
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
