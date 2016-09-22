<?php

namespace Api\Service;

use Api\Model\JobLocaleTable;
use Api\Model\JobTable;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;
use Zend\ServiceManager\FactoryInterface;

class JobService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = JobTable::class;
    protected $tableLocale = JobLocaleTable::class;

    public function getDetail($lang, $jobUri)
    {
        $this->table->setTableLocaleService($this->tableLocale);
        $tableFields = array (
            'id'                => 'id',
            'job_categories_id' => 'job_categories_id',
            'key'               => 'key',
            'image_url'         => 'image_url',
            'created_at'        => 'created_at',
            'updated_at'        => 'updated_at',
            'deteled_at'        => 'deleted_at',
            'active'            => 'active'
        );
        $localeFields = array(
            'title'            => 'title',
            'url_key'          => 'url_key',
            'content'          => 'content',
            'meta_description' => 'meta_description',
        );
        return $this->table->findRow($lang,'jobs_locales.url_key',$jobUri,$tableFields,$localeFields);
    }

    public function getData($lang, $categoryFilter = false, $page = 1,  $limit = 10)
    {
        $this->table->setTableLocaleService($this->tableLocale);

        $paginateCallback = function (&$select, &$where) use($lang, $categoryFilter) {
            $select->join(
                'job_categories',
                new Expression('job_categories.id =' . 'jobs.job_categories_id'),
                array(
                    "category_key" => "key"
                )
            )->join(
                'job_categories_locales',
                new Expression('job_categories_locales.related_table_id = job_categories.id AND languages.id = job_categories_locales.language_id'),
                array(
                    'category_title'            => 'title',
                    'category_url_key'          => 'url_key',
                    'category_meta_description' => 'meta_description'
                )
            )->order('jobs.created_at DESC');
            $where['job_categories.active'] = '1';

            if ($categoryFilter) {
                $where['job_categories_locales.url_key'] = $categoryFilter;
            }
        };

        $tableFields = array(
            'id'                => 'id',
            'job_categories_id' => 'job_categories_id',
            'key'               => 'key',
            'image_url'         => 'image_url',
            'created_at'        => 'created_at',
            'updated_at'        => 'updated_at',
            'active'            => 'active',
            'deleted_at'        => 'deleted_at'
        );
        $localeTableFields = array (
            'title'            => 'title',
            'url_key'          => 'url_key',
            'content'          => 'content',
            'meta_description' => 'meta_description',
        );

        $jobsPaginator = $this->table->paginate($lang, $tableFields, $localeTableFields, $paginateCallback);

        $jobsPaginator->setCurrentPageNumber($page);

        $jobsPaginator->setItemCountPerPage($limit);

        return $jobsPaginator;
    }
}