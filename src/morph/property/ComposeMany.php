<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */
namespace morph\property;
/**
 * Allows a Morph_Object to compose many Morph_Objects
 *
 * Composition means that the contained objects will not be
 * saved inidivually to the database. So, they will not get
 * ids and will not be individually retrievable
 *
 * @package Morph
 * @subpackage Property
 */
class  ComposeMany extends Generic
{

    /**
     * Holds the name of the class this property should contain
     * @var string
     */
    protected $type;


    /**
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type)
    {
        $this->type = $type;
        $default = new \morph\Collection();
        $default->setPermissableType($type);
        parent::__construct($name, $default);
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
        if ($value->count() > 0) {
            foreach ($value as $object) {
                $this->isPermissableType($object);
            }
        }
        $this->value = $value;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__setRawValue()
     */
    public function __setRawValue($value)
    {
        $collection = new \morph\collection();
        if (count($value) > 0) {
            foreach ($value as $item) {
                $object = new $this->type;
                $object->__setData($item, \morph\Enum::STATE_CLEAN);
                $collection->append($object);
            }
        }
        $this->value = $collection;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/Property/Morph_Property_Generic#__getRawValue()
     */
    public function __getRawValue()
    {
        $rawValue = array();
        if($this->value->count() > 0){
            //$rawValue = $this->Value->getArrayCopy();
            foreach ($this->value as $value) {
                $rawValue[] = $value->__getData();
            }
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
            throw new \RuntimeException(\get_class($object) . " is not an instance of {$this->Type}");
        }
    }

}