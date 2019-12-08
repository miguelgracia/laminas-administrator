<?php

namespace Administrator\Validator;

use Traversable;
use Zend\Stdlib\ArrayUtils;

/**
 * Validator which checks if the file is an image
 */
class IsImage extends \Zend\Validator\File\IsImage
{
    /**
     * Sets validator options
     *
     * @param array|Traversable|string $options
     */
    public function __construct($options = [])
    {
        parent::__construct($options);
    }

    public function isValid($value, $file = null)
    {
        $value = $_SERVER['DOCUMENT_ROOT'] . $value;
        return parent::isValid($value);
    }
}
