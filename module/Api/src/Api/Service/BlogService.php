<?php

namespace Api\Service;


use Api\Model\BlogLocaleTable;
use Api\Model\BlogTable;
use Zend\Db\Sql\Predicate\Expression;
use Zend\ServiceManager\FactoryInterface;

class BlogService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = BlogTable::class;
    protected $tableLocale = BlogLocaleTable::class;

    public function getDetail($lang, $blogUri)
    {
        $this->table->setTableLocaleService($this->tableLocale);
        return $this->table->findRow($lang,'blog_entries_locales.url_key',$blogUri);
    }

    public function getData($lang, $categoryFilter = false, $page = 1,  $limit = 1)
    {
        $this->table->setTableLocaleService($this->tableLocale);

        $blogsPaginator = $this->table->paginate($lang, function (&$select, &$where) use($lang, $categoryFilter) {
            $select->join(
                'blog_categories',
                new Expression('blog_categories.id =' . 'blog_entries.blog_categories_id'),
                array(
                    "category_key" => "key"
                )
            )->join(
                'blog_categories_locales',
                new Expression('blog_categories_locales.related_table_id = blog_categories.id AND languages.id = blog_categories_locales.language_id'),
                array(
                    'category_title'            => 'title',
                    'category_url_key'          => 'url_key',
                    'category_meta_description' => 'meta_description'
                )
            );
            $where['blog_categories.active'] = '1';

            if ($categoryFilter) {
                $where['blog_categories_locales.url_key'] = $categoryFilter;
            }
        });

        $blogsPaginator->setCurrentPageNumber($page);

        $blogsPaginator->setItemCountPerPage($limit);

        return $blogsPaginator;
    }
}