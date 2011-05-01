<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */
namespace morph\property;
/**
 * The property type allows the composition of another Morph_Object
 * derived class.  Objects placed in this kind of property are stored
 * as part of the parent object. They will not be given an id and
 * cannot be retrieved individually
 *
 * @package Morph
 * @subpackage Property
 */
class ComposeOne extends Generic
{

    /**
     * The class that this property should contain
     * @var string
     */
    protected $type;

    /**
     * Creates a property to contain a reference to another Morph_Object
     *
     * @param string $name The name to associate with this property
     * @param string $type The class that this property should contain
     * @return Morph_Object
     */
    public function __construct($name, $type)
    {
        $this->type = $type;
        parent::__construct($name);
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#getValue()
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#setValue()
     */
    public function setValue($value){
        $this->isPermissableType($value);
        $this->value = $value;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__setRawValue()
     */
    public function __setRawValue($value)
    {
        $this->value = new $this->type;
        if (null !== $value) {
        	$this->value->__setData($value, \morph\Enum::STATE_CLEAN);
        }
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__getRawValue()
     */
    public function __getRawValue()
    {
        $rawValue = null;
        if(isset($this->value)){
            $rawValue = $this->value->__getData();
        }
        return $rawValue;
    }

    /**
     *
     * @param $object
     * @return unknown_type
     */
    private function isPermissableType($object)
    {
        if(!($object instanceof $this->type) && !\is_null($object)){
            throw new \RuntimeException(\get_class($object) . " is not an instance of {$this->type}");
        }
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__toString()
     */
    public function __toString()
    {
        return $this->getName() . ": " . \json_encode($this->__getRawValue());
    }

}
