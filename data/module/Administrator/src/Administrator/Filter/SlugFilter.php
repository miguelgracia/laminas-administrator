<?php

namespace Administrator\Filter;

use Cocur\Slugify\Slugify;
use Laminas\Filter\AbstractFilter;
use Laminas\Filter\Exception;

class SlugFilter extends AbstractFilter
{
    protected $slugify = null;

    protected $separator = '-';

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

        $this->slugify = new Slugify($this->getOptions());
    }

    public function setSeparator($value)
    {
        $this->separator = $value;
        return $this;
    }

    public function setLowercase($value)
    {
        $this->lowercase = $value;
        return $this;
    }

    public function setRegexp($value)
    {
        $this->regexp = $value;
        return $this;
    }

    public function setRulesets($value)
    {
        $this->rulesets = $value;
        return $this;
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
        return $this->slugify->slugify($value, $this->separator);
    }
}
