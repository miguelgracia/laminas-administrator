<?php

namespace Administrator\Filter;

use Administrator\Validator\Youtube;
use Laminas\Dom\Document;
use Laminas\Filter\AbstractFilter;
use Laminas\Filter\Exception;
use Laminas\Filter\PregReplace;
use Laminas\Validator\Regex;

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

    protected $htmlTags = [];

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
        $pregReplaceFilter = new PregReplace([
            'pattern' => '/^(\/*media|\/)*/',
            'replacement' => $this->relativePath . '$2'
        ]);

        $youtubeValidator = new Youtube();

        if (is_array($value)) {
            foreach ($value as $i => &$val) {
                if (trim($val) == '') {
                    unset($value[$i]);
                    continue;
                }
                $val = $this->setSrc($val);
            }
        } elseif ($value != '') {
            if (!$youtubeValidator->isValid($value)) {
                $value = $pregReplaceFilter->filter($value);
            }
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
                        $hasClassAttribute = false;
                        foreach ($node->attributes as $attr) {
                            if ($attr->name == 'src') {
                                $attr->value = $this->setSrc($attr->value);
                            } elseif ($attr->name == 'class') {
                                $hasClassAttribute = true;
                                $attr->value = $attr->value . ' inline-element';
                            }
                        }
                        if (!$hasClassAttribute) {
                            $node->setAttribute('class', 'inline-element');
                        }
                    }
                }

                $value = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(['<html>', '</html>', '<body>', '</body>'], ['', '', '', ''], $dom->saveHTML()));
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
