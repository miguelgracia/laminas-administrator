<?php

namespace Administrator\Filter;

use Zend\Dom\Document;
use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;
use Zend\Filter\PregReplace;

/**
 * Class MediaUri
 * @package Administrator\Filter
 *
 * Añade el segmento indicado en $relativePath a la cadena especificada.
 * Admite búsqueda de elementos dentro de un html, por si queremos añadir
 * el prefijo al atributo src de los elementos que dispongan de dicho atributo
 *
 */
class MediaUri extends AbstractFilter
{
    protected $relativePath = '/media/';

    protected $htmlTags = array();

    /**
     * Sets filter options
     *
     * @param array|\Traversable|null $options
     */
    public function __construct($options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }
    }

    public function setHtmlTags($value)
    {
        $this->htmlTags = $value;
    }

    public function setRelativePath($path)
    {
        $this->relativePath = $path;
    }

    private function setSrc($value)
    {
        $pregReplaceFilter = new PregReplace(array(
            'pattern' => '/^(\/*media|\/)*/',
            'replacement' => $this->relativePath.'$2'
        ));

        if (is_array($value)) {
            foreach ($value as $i => &$val) {
                if (trim($val) == '') {
                    unset($value[$i]);
                    continue;
                }
                $val = $this->setSrc($val);
            }
        } elseif($value != '') {
            $value = $pregReplaceFilter->filter($value);
        }

        return $value;
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
        if (is_array($this->htmlTags) and count($this->htmlTags) > 0) {

            $currentValue = $value;

            try {
                $document = new Document($value);

                $dom = $document->getDomDocument();

                foreach ($this->htmlTags as $tag) {
                    $elems = $dom->getElementsByTagName($tag);

                    $nodeList = new Document\NodeList($elems);

                    foreach ($nodeList as $node) {
                        foreach ($node->attributes as $attr) {
                            if ($attr->name == 'src') {
                                $attr->value = $this->setSrc($attr->value);
                            }
                        }
                    }
                }

                $value = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
            } catch (\Exception $ex) {

                /*
                 * El parse del html ha fallado, bien por que $value es
                 * una cadena vacía o por cualquier otro motivo
                 */

                $value = $currentValue;
            }
        } else {
            $value = $this->setSrc($value);
        }

        return $value;
    }
}