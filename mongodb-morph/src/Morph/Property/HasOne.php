<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

/**
 * Class to represent a related object
 *
 * You can use this property type to store a reference to another
 * Morph_Object
 *
 * @package Morph
 * @subpackage Property
 */

class Morph_Property_HasOne extends Morph_Property_Generic
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
     * The class that this object should hold
     * @var string
     */
    protected $Type;

    /**
     *
     * @param string $name
     * @param string $type the classname this property should hold
     * @param $default
     * @return Morph_Property_hasOne
     */
    public function __construct($name, $type, Morph_Object $default = null)
    {
        $this->Type = $type;
        $this->isPermissableType($default);
        parent::__construct($name, $default);
    }


    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#setValue()
     */
    public function setValue($value)
    {
        $this->isPermissableType($value);
        $this->Value = $value;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#getValue()
     */
    public function getValue(){
        if(is_null($this->Value) && !is_null($this->Reference)){
            $this->Value = new $this->Type;
            $this->Value = Morph_Storage::instance()->fetchById($this->Reference['$id']);
        }
        return $this->Value;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__getRawValue()
     */
    public function __getRawValue()
    {
        if(!is_null($this->Value) && is_null($this->Reference)){
            if($this->Value->state() != Morph_Object::STATE_CLEAN){
                //save value
                Morph_Storage::instance()->save($this->Value);
            }
            $this->Reference = Morph_Utils::objectReference($this->Value);
        }
        return $this->Reference;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__setRawValue()
     */
    public function __setRawValue($value)
    {
        $this->Reference = $value;
    }

    /**
     *
     * @param $object
     * @throws RuntimeException If the object passed in is not off the correct type
     */
    private function isPermissableType($object)
    {
        if(!($object instanceof $this->Type) && !is_null($object)){
            throw new RuntimeException(get_class($object) . " is not an instance of {$this->Type}");
        }
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__toString()
     */
    public function __toString()
    {
        $id = '';
        if(isset($this->Value)){
            $id = $this->Value->id();
        }elseif(isset($this->Reference)){
            $id = $this->Reference['$id'];
        }
        return $this->getName() . ": {" . $this->Type . ": $id}";
    }

}
