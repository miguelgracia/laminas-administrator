<?php

namespace MediaDispatcher\View\Helper;


use Zend\View\Helper\AbstractHelper;

class DinamicUrlImage extends AbstractHelper
{
    protected $imagePath;

    public function __invoke($imagePath)
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    public function makeUrl($width = null, $height = null, $randomUrl = true)
    {
        $url = $this->getView()->getHelperPluginManager()->get('Url');

        $query = array(
            'path' => $this->imagePath,
        );

        if (is_numeric($width)) {
            $query['width'] = $width;
        }

        if (is_numeric($height)) {
            $query['height'] = $height;
        }

        return $url('dispatch/random',array(
            'rnd' => $randomUrl === true ? microtime(true) * 10000 : $randomUrl
        ),array(
            'query' => $query,
            'force_canonical' => true
        ));
    }
}