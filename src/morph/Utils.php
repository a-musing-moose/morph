<?php
/**
 * @package morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */
namespace morph;
/**
 * This class provides a few helper function for the Morph package
 *
 * @package morph
 */
class Utils
{

    /**
     * Gets the default collection name for the passed in Morph_Object
     *
     * @param $object
     * @return string
     */
    public static function collectionName(Object $object)
    {
        return \str_replace(array('_','\\'), '.', \get_class($object));
    }

    /**
     * Generates an object reference for Has* properties
     *
     * @param $object
     * @return array
     */
    public static function objectReference(Object $object)
    {
        return \MongoDBRef::create($object->collection(), $object->id());
    }
}
