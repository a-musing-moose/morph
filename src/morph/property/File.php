<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.co
 * @copyright Jonathan Moss 2009
 */
namespace morph\property;
/**
 * Class to represent a related object
 *
 * You can use this property type to store a reference to a file
 *
 * @package Morph
 * @subpackage Property
 */

class File extends Generic
{

    /**
     *
     * @var Morph_Object
     */
    protected $value;

    /**
     * Contains the reference details for this object
     * @var array
     */
    protected $reference;

    /**
     *
     * @var string
     */
    protected $filePath;

    /**
     *
     * @param string $name
     * @param string $type the classname this property should hold
     * @param $default
     * @return Morph_Property_hasOne
     */
    public function __construct($name)
    {
        parent::__construct($name, null);
    }


    /**
     * Takes the path to the file to be saved in MongoDB
     * @param string $value
     */
    public function setValue($value)
    {
        if (\file_exists($value)) {
            $this->filePath = \realpath($value);
            unset($this->value);
        } else {
            throw new \InvalidArgumentException("The file $value does not exist");
        }
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @return MongoGridFSFile
     */
    public function getValue(){
        if(!isset($this->value) && !\is_null($this->reference)){
            $this->value = \morph\Storage::instance()->fetchFile($this->reference);
        }
        return $this->value;
    }

    /**
     *
     * @return mixed The reference of the stored file
     */
    public function __getRawValue()
    {
        if (!is_null($this->filePath)) {
            $this->reference = \morph\Storage::instance()->saveFile($this->filePath, $this->reference);
        }
        return $this->reference;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__setRawValue()
     */
    public function __setRawValue($value)
    {
        unset($this->value);
        unset($this->filePath);
        $this->reference = $value;
    }

        /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__toString()
     */
    public function __toString()
    {
        $id = '';
        if (!\is_null($this->reference)) {
            $id = $this->reference;
        } elseif (!\is_null($this->filePath)) {
            $id = $this->filePath;
        }
        return $this->getName() . ": {File: $id}";
    }
}