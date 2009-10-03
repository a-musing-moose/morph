<?php
/**
 * @author Jonathan Moss
 * @copyright 2009 Jonathan Moss <xirisr@gmail.com>
 * @package Morph
 */

/**
 * Morph collection object
 *
 * This is designed to work with finder methods within Morph_Object
 *
 * This object behaves like an array (e.g. it is Iterable, Countable and
 * provides ArrayAccess).
 *
 * This collection class also provides a bunch of additional functionality
 * such as sorting, filtering, reducing etc
 *
 * @package Morph
 */
class Morph_Collection extends ArrayObject
{

    /**
     * @var int
     */
    private $totalCount;

    /**
     * The classname of the class this collection should allow
     * @var String
     */
    private $PermissableType = 'Morph_Object';

    /**
     * Sets the classname of the class this collection should allow
     * @param string $type
     * @return void
     */
    public function setPermissableType($type)
    {
        $this->PermissableType = (string) $type;
    }

    /**
     * Gets the total count
     *
     * Id $totalCount is set then it will set the total count first
     *
     * @param int $totalCount
     * @return void
     */
    public function setTotalCount($totalCount = null)
    {
        if (!is_null($totalCount)) {
            $this->totalCount = (int) $totalCount;
        }
        return $this->totalCount;
    }

    /**
     * Returns the total count if set
     *
     * @return int
     */
    public function totalCount()
    {
        return $this->totalCount;
    }

    // UTILITY FUNCTIONS

    /**
     * Iterates over each value in the collection passing them to the callback function.
     *
     * If the callback function returns true, the current value from input is returned into the result array. Array keys are preserved.
     *
     * If no callback is provided and values which equate to false will be removed
     *
     * A copy of this object is returned but with the filter applied
     *
     * @param $callback
     * @return Morph_Array
     */
    public function filter($callback = null)
    {
        return new self(array_filter($this->getArrayCopy(), $callback));
    }

    /**
     * Performs $callback on each object within the array
     *
     * The callback function must take a minimum of 2 parameter $object and $key.
     * A third optional parameter is also supported which will be passed $userdata
     *
     * @param callback $callback The callback function that should be applied to each object
     * @param mixed $userdata If supplied is passed a third parameter to $callback
     * @return Morph_Collection this object but with the callback applied
     */
    public function walk($callback, $userdata = null)
    {
        $copy = $this->getArrayCopy();
        array_walk($copy, $callback, $userdata);
        return new self($copy);
    }

    /**
     * Iteratively reduces this collection to a single value using a callback function
     *
     *  applies iteratively the function $callback to the elements of the array input,
     *  so as to reduce the array to a single value.
     *
     * @param callback $callback
     * @param mixed $initial
     * @return mixed
     */
    public function reduce($callback, $initial = null)
    {
        return array_reduce($this->getArrayCopy(), $callback, $initial);
    }

    /**
     * Sorts this collection using the specified Morph_ICompare object
     *
     * @param Morph_ICompare $comparator
     * @return boolean
     */
    public function comparatorSort(Morph_ICompare $comparator)
    {
        $copy = $this->getArrayCopy(); //ArrayObject
        $sorted = usort($copy,array($comparator, 'compare'));
        $this->exchangeArray($copy);
        return $sorted;
    }

    /**
     * Sorts this collection using the specified Morph_ICompare object
     *
     * Preserves keys
     *
     * @param $comparator
     * @return boolean
     */
    public function comparatorASort(Morph_ICompare $comparator)
    {
        return $this->uasort(array($comparator, 'compare'));
    }

    /**
     * Sorts this collection based on the specified $propertyName
     *
     * @param $propertyName
     * @return boolean
     */
    public function propertySort($propertyName, $preserveKeys = false)
    {
        $comparator = new Morph_compare_Property($propertyName);
        $sorted = null;
        if($preserveKeys === true){
            $sorted = $this->comparatorASort($comparator);
        }else{
            $sorted = $this->comparatorSort($comparator);
        }
        return $sorted;
    }

    /**
     * Sorts this collection numerically based on the specified $propertyName
     *
     * @param $propertyName
     * @return boolean
     */
    public function numericPropertySort($propertyName, $preserveKeys = false)
    {
        $comparator = new Morph_compare_NumericProperty($propertyName);
        $sorted = null;
        if($preserveKeys === true){
            $sorted = $this->comparatorASort($comparator);
        }else{
            $sorted = $this->comparatorSort($comparator);
        }
        return $sorted;
    }


    // ARRAY ACCESS FUNCTIONS

    /**
     * Appends the given $object to the collection
     *
     * This function override the parent append to do type checking
     *
     */
    public function append($object)
    {
        $this->checkType($object);
        return parent::append($object);
    }

    /**
     * Sets the given offset to the given $object
     *
     * This function override the parent offsetSet to do type checking
     *
     */
    public function offsetSet($offset, $object)
    {
        $this->checkType($object);
        return parent::offsetSet($offset, $object);
    }

    /**
     * Checks that $object is an instance of the permissable type
     *
     * Throws a RuntimeException if not
     *
     * @param $object
     */
    private function checkType($object){
        if(!($object instanceof $this->PermissableType)){
            throw new RuntimeException('object of type' . get_class($object) . ' does not extend ' . $this->PermissableType);
        }
    }
}
