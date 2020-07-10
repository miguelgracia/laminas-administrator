<?php

namespace AmMedia\FileManager;

use Laminas\Stdlib\AbstractOptions;

class FileManagerOptions extends AbstractOptions
{
    /**
     * We use the __ prefix to avoid collisions with properties in
     * user-implementations.
     *
     * @var bool
     */
    protected $__strictMode__ = false;

    public function __call($name, $arguments)
    {
        preg_match('/^(get|set)(.+)/', $name, $output_array);

        if (isset($output_array[1])) {
            $property = $output_array[2];
            switch ($output_array[1]) {
                case 'get':
                    return $this->{$property};
                    break;
                case 'set':
                    $this->{$property} = $arguments[0];
                    return $this;
                    break;
            }
        }
    }
}
