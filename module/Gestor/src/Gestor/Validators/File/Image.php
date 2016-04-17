<?php
namespace Gestor\Validators\File;

//use Gestor\Validator\FileValidatorInterface;
use Zend\Validator\File\Extension;
use Zend\File\Transfer\Adapter\Http;
use Zend\Validator\File\FilesSize;
use Zend\Filter\File\Rename;
use Zend\Validator\File\MimeType;
use Zend\Validator\AbstractValidator;

class Image extends AbstractValidator
{
    const FILE_EXTENSION_ERROR  = 'invalidFileExtention';
    const FILE_NAME_ERROR       = 'invalidFileName';
    const FILE_INVALID          = 'invalidFile';
    const FALSE_EXTENSION       = 'fileExtensionFalse';
    const NOT_FOUND             = 'fileExtensionNotFound';
    const TOO_BIG               = 'fileFilesSizeTooBig';
    const TOO_SMALL             = 'fileFilesSizeTooSmall';
    const NOT_READABLE          = 'fileFilesSizeNotReadable';


    public $minSize = 64;  //KB
    public $maxSize = 1024; //KB
    public $overwrite = true;
    public $newFileName = null;
    public $uploadPath = './data/';
    public $extensions = array('jpg', 'png', 'gif', 'jpeg');
    public $mimeTypes = array(
        'image/gif',
        'image/jpg',
        'image/png',
    );

    protected $messageTemplates = array(
        self::FILE_EXTENSION_ERROR  => "File extension is not correct",
        self::FILE_NAME_ERROR       => "File name is not correct",
        self::FILE_INVALID          => "File is not valid",
        self::FALSE_EXTENSION       => "File has an incorrect extension",
        self::NOT_FOUND             => "File is not readable or does not exist",
        self::TOO_BIG               => "All files in sum should have a maximum size of '%max%' but '%size%' were detected",
        self::TOO_SMALL             => "All files in sum should have a minimum size of '%min%' but '%size%' were detected",
        self::NOT_READABLE          => "One or more files can not be read",
    );

    protected $fileAdapter;

    protected $validators;

    protected $filters;

    public function __construct($options)
    {
        $this->fileAdapter = new Http();
        parent::__construct($options);
    }

    public function isValid($fileInput)
    {
        $options = $this->getOptions();
        $extensions = $this->extensions;
        $minSize    = $this->minSize;
        $maxSize    = $this->maxSize;
        $newFileName = $this->newFileName;
        $uploadPath = $this->uploadPath;
        $overwrite = $this->overwrite;

        if (array_key_exists('extensions', $options)) {
            $extensions = $options['extensions'];
        }
        if (array_key_exists('minSize', $options)) {
            $minSize = $options['minSize'];
        }
        if (array_key_exists('maxSize', $options)) {
            $maxSize = $options['maxSize'];
        }
        if (array_key_exists('newFileName', $options)) {
            $newFileName = $options['newFileName'];
        }
        if (array_key_exists('uploadPath', $options)) {
            $uploadPath = $options['uploadPath'];
        }
        if (array_key_exists('overwrite', $options)) {
            $overwrite = $options['overwrite'];
        }
        $fileName   = $fileInput['name'];
        $fileSizeOptions = null;
        if ($minSize) {
            $fileSizeOptions['min'] = $minSize*1024 ;
        }
        if ($maxSize) {
            $fileSizeOptions['max'] = $maxSize*1024 ;
        }
        if ($fileSizeOptions) {
            $this->validators[] = new FilesSize($fileSizeOptions);
        }
        $this->validators[] = new Extension(array('extension' => $extensions));
        if (! preg_match('/^[a-z0-9-_]+[a-z0-9-_\.]+$/i', $fileName)) {
            $this->error(self::FILE_NAME_ERROR);
            return false;
        }

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        if (! in_array($extension, $extensions)) {
            $this->error(self::FILE_EXTENSION_ERROR);
            return false;
        }
        if ($newFileName) {
            $destination = $newFileName.".$extension";
            if (! preg_match('/^[a-z0-9-_]+[a-z0-9-_\.]+$/i', $destination)) {
                $this->error(self::FILE_NAME_ERROR);
                return false;
            }
        } else {
            $destination = $fileName;
        }
        $renameOptions['target'] = $uploadPath.$destination;
        $renameOptions['overwrite'] = $overwrite;
        $this->filters[] = new Rename($renameOptions);
        $this->fileAdapter->setFilters($this->filters);
        $this->fileAdapter->setValidators($this->validators);
        if ($this->fileAdapter->isValid()) {
            $this->fileAdapter->receive();
            return true;
        } else {
            $messages = $this->fileAdapter->getMessages();
            if ($messages) {
                $this->setMessages($messages);
                foreach ($messages as $key => $value) {
                    $this->error($key);
                }
            } else {
                $this->error(self::FILE_INVALID);
            }
            return false;
        }
    }

}