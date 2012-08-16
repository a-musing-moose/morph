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
     * The data associated with this object
     * @var \morph\PropertySet
     */
    protected $propertySet;

    /**
     * The collections of validators for propertys
     * @var array
     */
    protected $validators;

    /**
     * @param  string $id If supplied this will be the id used to reference this object
     */
    public function __construct($id = null)
    {
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
        return $this->propertySet->getState();
    }

    /**
     * Adds a new property to this object
     *
     * @param  \morph\property\Generic $property
     * @param  string $alias (Default: null)
     * @return \morph\Object
     */
    protected function addProperty(\morph\property\Generic $property, $alias = null)
    {
        $this->propertySet[$property->getName()] = $property;
        if (null !== $alias) {
        	$this->propertySet->setStorageName($property->getName(), $alias);
        }
        return $this;
    }

    /**
     * Sets the property data for this object
     *
     * @param  array  $data
     * @param  string $state (Default: Enum::STATE_DIRTY)
     * @return \morph\Object
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
            $this->propertySet->__setRawPropertyValue($propertyName, $value, $state);
        }
        return $this;
    }

    /**
     * Gets the property data for this object
     *
     * @param  boolean  $whereNew  Whether or not to only get dirty/new properties.
     * @return array
     */
    public function __getData($whereNew = false)
    {
        $data = array();
        if (!is_null($this->id)) {
            $data['_id'] = $this->id;
        }
        $data['_ns'] = \get_class($this);
        foreach ($this->propertySet as $property) {
            $storageName = $this->propertySet->getStorageName($property->getName());

            if (!$whereNew) {
                $data[$storageName] = $property->__getRawValue();
            } else {
                $state = $property->getState();

                if ($state === \morph\Enum::STATE_DIRTY || $state === \morph\Enum::STATE_NEW) {
                    $data[$storageName] = $property->__getRawValue();
                }
            }
        }

        return $data;
    }

    /**
     * Returns the PropertySet for this object
     * 
     * @return \morph\PropertySet
     */
    public function __getPropertySet()
    {
    	return $this->propertySet;
    }

    // ********************** //
    // MAGIC ACCESS FUNCTIONS //
    // ********************** //

    /**
     * Gets a property by name if it exists. Issues an E_USER_WARNING if it does not exist.
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
     * Sets a property by name and value
     * 
     * @param  string $propertyName
     * @param  string $propertyValue
     * @return \morph\Object
     */
    public function __set($propertyName, $propertyValue)
    {
        if (\array_key_exists($propertyName, $this->propertySet)) {
            $this->propertySet[$propertyName]->setValue($propertyValue);
        }else{
            $this->addProperty(new \morph\property\Generic($propertyName, $propertyValue));
            \trigger_error("The property $propertyName was not found in object of class " . \get_class($this) . ' but I have added it as a generic property type', E_USER_WARNING);
        }
        return $this;
    }

    /**
     * Clones the current object
     */
    public function __clone()
    {
        $this->id = null;
    }

    // ********************* //
    // PERSISTANCE FUNCTIONS //
    // ********************* //

    /**
     * Saves this object
     *
     * @param  array $options Support the same options as MongoCollection::save()
     * @return \morph\Object
     */
    public function save(array $options = array())
    {
        return Storage::instance()->save($this, $options);
    }

    /**
     * Attempts to load the current object with data from the document id specified
     *
     * By default Morph sets the id to be an instance of MongoId().  When searching you need
     * to ensure you do the same by wrapping your id string in a MongoId object
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
     * By default Morph sets the id to be an instance of MongoId().  When searching you need
     * to ensure you do the same by wrapping your id string in a MongoId object
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
     * @param  IQuery $query
     * @return \morph\Iterator
     */
    public function findByQuery(IQuery $query)
    {
        return Storage::instance()->findByQuery($this, $query);
    }
    
    /**
     * Deletes objects by query
     *
     * @param  IQuery $query
     * @param  boolean $safe
     * @return boolean
     */
    public function deleteByQuery(IQuery $query, $safe = null)
    {
        return Storage::instance()->findByQuery($this, $query, $safe);
    }

    /**
     * Finds one object by query
     *
     * @param  IQuery $query
     * @return Object
     */
    public function findOneByQuery(IQuery $query)
    {
        return Storage::instance()->findOneByQuery($this, $query);
    }

    /**
     * Returns all entries for the current document
     *
     * @return \morph\Iterator
     */
    public function fetchAll()
    {
        return Storage::instance()->findByQuery($this);
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
        // create the array that we will be encoding and returning
        // also put inside the array the mongodb ID and the 'state'
        $data = array(
            'Id' => $this->id(),
            'State' => $this->state()
        );

        // iterate through all the properties this object has and print them out
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
