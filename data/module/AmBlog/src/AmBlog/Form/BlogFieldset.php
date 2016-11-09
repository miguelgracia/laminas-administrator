<?php

namespace AmBlog\Form;

use Administrator\Filter\MediaUri;
use Administrator\Form\AdministratorFieldset;
use AmBlog\Model\BlogModel;
use AmBlog\Model\BlogTable;

class BlogFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = BlogTable::class;

    public function initializers()
    {
        $serviceLocator = $this->serviceLocator->getServiceLocator();

        return array(
            'fieldModifiers' => array(
                'blogCategoriesId' => 'Select'
            ),
            'fieldValueOptions' => array(
                'blogCategoriesId' => function () use($serviceLocator) {
                    return $serviceLocator->get('AmBlogCategory\Model\BlogCategoryTable')->all()->toKeyValueArray('id','key');
                },
                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
            )
        );
    }

    public function addFields()
    {
        $imageUrl = $this->get('imageUrl');
        $class = $imageUrl->getAttribute('class');
        $class .= ' browsefile';
        $imageUrl->setAttribute('class',$class);
        $imageUrl->setAttribute('readonly','readonly');

        $youtubeVideos = $this->serviceLocator->getServiceLocator()->get('YoutubeService')->getVideosInDatabase();

        $imageUrl->setAttribute('data-youtube',json_encode($youtubeVideos->toObjectArray()));

        $imageUrl->setOption('partial_view','am-blog/am-blog-module/form-partial/image-url');
        $imageUrl->setOption('allow_add_multiple_files', true);
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilter['imageUrl']['filters'][] = array(
            'name' => MediaUri::class
        );

        return $inputFilter;
    }
}