<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <jonathan.moss@tangentlabs.co.uk>
 * @copyright Jonathan Moss 2009
 */

/**
 * This property type represents a collection of many Morph_Objects.
 *
 * Each object contained within this collection is stored separately as it's own object,
 * independent of the parent object
 *
 * @package Morph
 * @subpackage Property
 */
class  Morph_Property_HasMany extends Morph_Property_Generic implements ArrayAccess
{

    /**
     * @var boolean
     */
    protected $Loaded = false;

    /**
     * @var String
     */
    protected $Type;

    /**
     * @var array
     */
    protected $References;


    /**
     *
     * @param String $name
     * @param String $type
     * @return Morph_Property_HasMany
     */
    public function __construct($name, $type)
    {
        $this->Type = $type;
        $this->Value = new Morph_Collection();
        $this->Value->setPermissableType($type);
        parent::__construct($name);
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__setRawValue()
     */
    public function __setRawValue($value)
    {
        $this->References = $value;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#__getRawValue()
     */
    public function __getRawValue()
    {
        if(count($this->Value) > 0){
            $refs = array();
            foreach ($this->Value as $object){
                if($object->state() != Morph_Object::STATE_CLEAN){
                    $this->Storage->save($object);
                }
                $refs[] = Morph_Utils::objectReference($object);
            }
            $this->References = $refs;
        }
        return $this->Reference;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#getValue()
     */
    public function getValue()
    {
        if($this->Loaded === false){
            $this->loadFromReferences();
        }
        return $this->Value;
    }

    /**
     * (non-PHPdoc)
     * @see tao/classes/Morph/property/Morph_Property_Generic#setValue()
     */
    public function setValue($value)
    {
        if($value instanceof Morph_Collection){
            $this->Value = $value;
            $this->References = array();
        }
    }


    /**
     * Loads the stored references
     * @return void
     */
    private function loadFromReferences()
    {
        $ids = array();
        foreach ($this->References as $reference){
            $ids[] = $reference['$id'];
        }

        $query = new Morph_Query();
        $query->property('_id')->in($ids);

        $object = new $this->Type;

        //@todo this could get nasty with large collections!
        $this->Value = $this->Storage->findByQuery($object, $query)->toCollection();
        $this->Loaded = true;
    }
}
?>