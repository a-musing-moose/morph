<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */
namespace morph\property;
/**
 * Class to represent a related object
 *
 * You can use this property type to store a reference to another
 * Morph_Object
 *
 * @package Morph
 * @subpackage Property
 */

class HasOne extends Generic
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
     * The class that this object should hold
     * @var string
     */
    protected $type;

    /**
     *
     * @param string $name
     * @param string $type the classname this property should hold
     * @param $default
     * @return Morph_Property_hasOne
     */
    public function __construct($name, $type, \morph\Object $default = null)
    {
        $this->type = $type;
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
        if ($this->state == \morph\Enum::STATE_CLEAN) {
            $this->state = \morph\Enum::STATE_DIRTY;
        }
        $this->value = $value;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#getValue()
     */
    public function getValue(){
        if(\is_null($this->value) && !\is_null($this->reference)) {
            $this->value = new $this->type;
            $this->value = \morph\Storage::instance()->fetchByDbRef($this->value, $this->reference);
        }
        return $this->value;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__getRawValue()
     */
    public function __getRawValue()
    {
        if(!is_null($this->value)) {
            if($this->value->state() != \morph\Enum::STATE_CLEAN) {
                //save value
                \morph\Storage::instance()->save($this->value);
            }
            $this->reference = \morph\Utils::objectReference($this->value);
        }
        return $this->reference;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__setRawValue()
     */
    public function __setRawValue($value, $state = null)
    {
        $this->reference = $value;
        if (null !== $state) {
            $this->state = $state;
        }
    }

    /**
     *
     * @param $object
     * @throws RuntimeException If the object passed in is not off the correct type
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
        $id = '';
        if(isset($this->value)){
            $id = $this->value->id();
        }elseif(isset($this->reference)){
            $id = $this->reference['$id'];
        }
        return $this->getName() . ": {" . $this->type . ": $id}";
    }
}
