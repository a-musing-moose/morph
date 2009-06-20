<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

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
class  Morph_Property_ComposeMany extends Morph_Property_Generic
{

    /**
     * Holds the name of the class this property should contain
     * @var string
     */
    protected $Type;


    public function __construct($name, $type)
    {
        $this->Type = $type;
        $this->Value = new Morph_Collection();
        $this->Value->setPermissableType($type);
        parent::__construct($name);
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#getValue()
     */
    public function getValue()
    {
        return $this->Value;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#setValue()
     */
    public function setValue(Morph_Collection $value){
        if ($value->count() > 0) {
            foreach ($value as $object) {
                $this->isPermissableType($object);
            }
        }
        $this->Value = $value;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__setRawValue()
     */
    public function __setRawValue($value)
    {
        $collection = new Morph_Collection();
        if (count($value) > 0) {
            foreach ($value as $item) {
                $object = new $this->Type;
                $object->__setData($item, Morph_Object::STATE_CLEAN);
                $collection->append($object);
            }
        }
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/Property/Morph_Property_Generic#__getRawValue()
     */
    public function __getRawValue()
    {
        $rawValue = array();
        if($this->Value->count() > 0){
            $rawValue = $this->Value->getArrayCopy();
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
        if(!($object instanceof $this->Type) && !is_null($object)){
            throw new RuntimeException(get_class($object) . " is not an instance of {$this->Type}");
        }
    }

}
?>