<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.co
 * @copyright Jonathan Moss 2009
 */

/**
 * Class to represent a related object
 *
 * You can use this property type to store a reference to a file
 *
 * @package Morph
 * @subpackage Property
 */

class Morph_Property_File extends Morph_Property_Generic
{

    /**
     *
     * @var Morph_Object
     */
    protected $Value;

    /**
     * Contains the reference details for this object
     * @var array
     */
    protected $Reference;

    /**
     *
     * @var string
     */
    protected $FilePath;

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
        if (file_exists($value)) {
            $this->FilePath = realpath($value);
            unset($this->Value);
        } else {
            throw new InvalidArgumentException("The file $value does not exist");
        }
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @return MongoGridFSFile
     */
    public function getValue(){
        if(is_null($this->Value) && !is_null($this->Reference)){
            $this->Value = $this->Storage->fetchFile($this->Reference);
        }
        return $this->Value;
    }

    /**
     *
     * @return mixed The reference of the stored file
     */
    public function __getRawValue()
    {
        if (!is_null($this->FilePath)) {
            $this->Reference = $this->Storage->saveFile($this->FilePath, $this->Reference);
        }
        return $this->Reference;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__setRawValue()
     */
    public function __setRawValue($value)
    {
        unset($this->Value);
        unset($this->FilePath);
        $this->Reference = $value;
    }

        /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__toString()
     */
    public function __toString()
    {
        $id = '';
        if (!is_null($this->Reference)) {
            $id = $this->Reference;
        } elseif (!is_null($this->FilePath)) {
            $id = $this->FilePath;
        }
        return $this->getName() . ": {File: $id}";
    }
}