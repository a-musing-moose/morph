<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2009 Jonathan Moss
 * @version SVN: $Id$
 */

/**
 * An iterator to hold results of a query
 *
 * @package Morph
 */
class Morph_Iterator implements Iterator
{

    /**
     * @var Morph_Object
     */
    private $type;

    /**
     * @var MongoCursor
     */
    private $cursor;

    /**
     *
     * @var Morph_Storage
     */
    private $storage;

    /**
     * Creates a new Morph_Iterator from the passed in cursor
     *
     * @param Morph_Object $object
     * @param MongoCursor $cursor
     * @return Morph_Iterator
     */
    public function __construct(Morph_Object $object, MongoCursor $cursor)
    {
        $this->type = $object;
        $this->cursor = $cursor;
    }

    /**
     * Returns the total count of result irrevelent of the value of limit
     *
     * @return int
     */
    public function totalCount()
    {
        return $this->cursor->count();
    }

    /**
     * Converts this iterator into an instance of Morph_Collection
     *
     * Note that this means all objects will be held in memory so
     * you need to be a bit careful not to exceed memory limits
     *
     * @return Morph_Collection
     */
    public function toCollection()
    {

        $collection = new Morph_Collection();
        $collection->setPermissableType(get_class($this->type));
        $collection->setTotalCount($this->totalCount());

        $this->rewind();
        foreach ($this as $object) {
            $collection->append($object);
        }

        return $collection;
    }

    /**
     * Converts a single mongo document into the appropriate Morph_Object
     *
     * @param array $item
     * @return Morph_Object
     */
    private function createObject(array $item)
    {
        $class = get_class($this->type);
        $object = new $class;
        $object->__setData($item, Morph_Enum::STATE_CLEAN);
        return $object;
    }

    // TRAVERSALABLE INTERFACE FUNCTIONS

    /**
     * Returns the current Morph_Object
     * @return Morph_Object
     */
    public function current()
    {
        $current = $this->cursor->current();
        return $this->createObject($current);
    }

    /**
     * Returns the key of the current position in the Iterator
     *
     * @return int
     */
    public function key()
    {
        return $this->cursor->key();
    }

    /**
     * Advances the pointer
     *
     * @return void
     */
    public function next()
    {
        return $this->cursor->next();
    }

    /**
     * Resets the internal pointer
     *
     * @return void
     */
    public function rewind()
    {
        return $this->cursor->rewind();
    }

    /**
     * Returns true only if the current pointer position is valid
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->cursor->valid();
    }

}
