<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */
namespace morph;
/**
 * A base class for creating model objects for storing in MongoDB.
 *
 * @package Morph
 */
class Object
{
    /**
     * The name of the collection this object is stored within
     * @var string
     */
    protected $collection;

    /**
     * The unique id for this object
     * @var string
     */
    protected $id;

    /**
     * The current state of this object
     *
     * one of Morph_Enum::STATE_*
     * @var string
     */
    protected $state;

    /**
     * The data associated with this object
     * @var Morph_PropertySet
     */
    protected $propertySet;

    /**
     * The collections of validators for propertys
     * @var array
     */
    protected $validators;

    /**
     * @param string $id If supplied this will be the id used to reference this object
     * @return Morph_Object
     */
    public function __construct($id = null)
    {
        $this->state = Enum::STATE_NEW;
        $this->id = $id;
        $this->propertySet = new PropertySet();
        $this->validators = array();
    }

    // *********************** //
    // GETTER/SETTER FUNCTIONS //
    // *********************** //

    /**
     * Returns the name of the collection this object should be stored in
     *
     * @param string $collection
     * @return string
     */
    public function collection($collection = null)
    {
        if (!\is_null($collection)) {
            $this->collection = $collection;
        }elseif (\is_null($this->collection)) {
            //defaults collection name to class name
            $this->collection = Utils::collectionName($this);
        }
        return $this->collection;
    }

    /**
     * Returns the Id of this object
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Returns the state of this object
     *
     * Will be one of Morph_Enum::STATE_*
     *
     * @return string
     */
    public function state()
    {
        return $this->state;
    }

    /**
     * Adds a new property to this object
     *
     * @param Morph_Property_Generic $property
     * @return Morph_Object
     */
    protected function addProperty(\morph\property\Generic $property)
    {
        $this->propertySet[$property->getName()] = $property;
        return $this;
    }

    /**
     * Sets the property data for this object
     *
     * @param $data
     * @param $state
     * @return Morph_Object
     */
    public function __setData(array $data, $state = Enum::STATE_DIRTY)
    {
        if (\array_key_exists('_id', $data)) {
            $this->id = $data['_id'];
            unset($data['_id']);
        }
        if (\array_key_exists('_ns', $data)) {
            unset($data['_ns']);
        }
        foreach ($data as $propertyName => $value) {
            $this->propertySet->__setRawPropertyValue($propertyName, $value);
        }
        $this->state = $state;
        return $this;
    }

    /**
     * Gets the property data for this object
     *
     * @return array
     */
    public function __getData()
    {
        $data = array();
        if (!is_null($this->id)) {
            $data['_id'] = $this->id;
        }
        $data['_ns'] = \get_class($this);
        foreach($this->propertySet as $property) {
            $data[$property->getName()] = $property->__getRawValue();
        }
        return $data;
    }

    // ********************** //
    // MAGIC ACCESS FUNCTIONS //
    // ********************** //

    /**
     *
     * @param $propertyName
     * @return mixed
     */
    public function __get($propertyName)
    {
        $value = null;
        if (\array_key_exists($propertyName, $this->propertySet)) {
            $value = $this->propertySet[$propertyName]->getValue();
        }else{
            \trigger_error("The property $propertyName was not found in object of class " . \get_class($this), E_USER_WARNING);
        }
        return $value;
    }

    /**
     *
     * @param $propertyName
     * @param $propertyValue
     * @return Morph_Object
     */
    public function __set($propertyName, $propertyValue)
    {
        if (\array_key_exists($propertyName, $this->propertySet)) {
            $this->propertySet[$propertyName]->setValue($propertyValue);
            if ($this->state == Enum::STATE_CLEAN) {
                $this->state = Enum::STATE_DIRTY;
            }
        }else{
            $this->addProperty(new \morph\property_Generic($propertyName, $propertyValue));
            \trigger_error("The property $propertyName was not found in object of class " . \get_class($this) . ' but I have added it as a generic property type', E_USER_WARNING);
        }
        return $this;
    }

    // ********************* //
    // PERSISTANCE FUNCTIONS //
    // ********************* //

    /**
     * Saves this object
     *
     * @param array $options Support the same options as MongoCollection::save()
     * @return Morph_Object
     */
    public function save(array $options = array())
    {
        return Storage::instance()->save($this, $options);
    }

    /**
     * Attempts to load the current object with data from the document id specified
     *
     * @param mixed $id
     * @return Morph_Object
     */
    public function loadById($id)
    {
        return Storage::instance()->fetchById($this, $id);
    }

    /**
     * Fetch multiple objects by their ids
     *
     * @param array $ids
     * @return Morph_Iterator
     */
    public function findByIds(array $ids)
    {
        return Storage::instance()->fetchByIds($this, $ids);
    }

    /**
     * Find objects by query
     *
     * @param Morph_IQuery $query
     * @return Morph_Iterator
     */
    public function findByQuery(IQuery $query)
    {
        return Storage::instance()->findByQuery($this, $query);
    }

    /**
     * Finds one object by query
     *
     * @param Morph_Query $query
     * @return Morph_Object
     */
    public function findOneByQuery(IQuery $query)
    {
        return Storage::instance()->findOneByQuery($this, $query);
    }

    /**
     * Deletes this object from the database
     * 
     * @return boolean
     */
    public function delete()
    {
    	return Storage::instance()->delete($this);
    }

    // ***************** //
    // UTILITY FUNCTIONS //
    // ***************** //

    /**
     * Converts this object to a string
     *
     * Useful for debugging output but not much else
     *
     * @return string
     */
    public function __toString()
    {
        $data = array(
            'Id' => $this->id(),
            'State' => $this->state()
        );
        foreach ($this->propertySet as $name => $property) {
            $data[$name] = (string)$property;
        }
        return \json_encode($data);
    }

    /**
     * Returns the content of this object as an array suitable for merging with a template
     *
     * @param $depth The maximum depth to traverse the tree
     * @return array
     */
    public function __toArray($depth = 1)
    {
        $content = array();
        if (!empty($this->id)) {
            $content['_id'] = $this->id;
        }
        --$depth;
        foreach ($this->propertySet as $property) {
            $value = $property->getValue();

            switch (true) {
                case ($property instanceof \morph\property\ComposeOne):
                    $content[$value->getName()] = $value->__toArray($depth);
                    break;
                case ($property instanceof morph\Property\ComposeMany):
                    $valueContents = array();
                    foreach ($value as $object) {
                        $valueContents[] = $object->__toArray($depth);
                    }
                    $content[$property->getName()] = $valueContents;
                    break;
                case ($value instanceof Object):
                    if ($depth > 0) {
                        $content[$value->getName()] = $value->__toArray($depth);
                    }
                    break;
                case ($value instanceof Collection):
                    if ($depth > 0) {
                        $valueContents = array();
                        foreach ($value as $object) {
                            $valueContents[] = $object->__toArray($depth);
                        }
                        $content[$property->getName()] = $valueContents;
                    }
                    break;
                default:
                    $content[$property->getName()] = $value;
                    break;
            }

        }
        return $content;
    }
}
