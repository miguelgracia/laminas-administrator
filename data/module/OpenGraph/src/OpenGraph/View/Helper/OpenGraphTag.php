<?php

namespace OpenGraph\View\Helper;

use Administrator\Validator\IsImage;
use Laminas\View\Helper\AbstractHelper;

class OpenGraphTag extends AbstractHelper
{
    protected $props = [
        'title' => 'og:title',
        'image' => 'og:image',
        'width' => 'og:image:width',
        'height' => 'og:image:height',
        'description' => 'og:description',
        'type' => 'og:type',
        'fb_id' => 'fb:app_id',
        'url' => 'og:url'
    ];

    public function __invoke()
    {
        return $this;
    }

    protected function openTag()
    {
        return "\t" . '<meta property="%s" content="%s" >' . "\n";
    }

    protected function metaTag()
    {
        return "\t" . '<meta name="%s" content="%s" >' . "\n";
    }

    public function render($openGraph)
    {
        $isImageValidator = new IsImage();

        foreach ($openGraph as $key => $item) {
            if (is_array($item)) {
                if (array_key_exists($key, $this->props)) {
                    foreach ($item as $itemValue) {
                        if ($key == 'image') {
                            if (!$isImageValidator->isValid($itemValue)) {
                                continue;
                            }
                            $itemValue = 'http://laminas-admin.local' . $itemValue;
                        }
                        echo sprintf($this->openTag(), $this->props[$key], $itemValue);
                    }
                }
            } else {
                if (array_key_exists($key, $this->props)) {
                    echo sprintf($this->openTag(), $this->props[$key], $item);
                    if ($key == 'description') {
                        echo sprintf($this->metaTag(), 'description', $item);
                    }
                }
            }
        }
    }
}
