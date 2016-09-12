<?php

namespace Administrator\Filter;

use Zend\Dom\Document;
use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;
use Zend\Filter\PregReplace;

class MediaUri extends AbstractFilter
{
    protected $relativePath = '/media/';

    public function setBaseUrl($value)
    {
        $this->baseUrl = $value;
    }

    public function setRelativePath($path)
    {
        $this->relativePath = $path;
    }

    private function setSrc(&$attr)
    {
        $pregReplaceFilter = new PregReplace(array(
            'pattern' => '/^(\/*media|\/)*/',
            'replacement' => $this->relativePath.'$2'
        ));

        $attr->value = $pregReplaceFilter->filter($attr->value);
    }


    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        $document = new Document($value);

        $dom = $document->getDomDocument();

        $mediaElements = array(
            'images' => $dom->getElementsByTagName('img'),
            'video_source' => $dom->getElementsByTagName('source'),
        );

        foreach ($mediaElements as $elementType => $elems) {
            $nodeList = new Document\NodeList($elems);

            foreach ($nodeList as $node) {
                foreach ($node->attributes as $attr) {
                    if ($attr->name == 'src') {
                        $this->setSrc($attr);
                    }
                }
            }
        }

        $value = $dom->saveHTML();

        return $value;
    }

}