<?php
/**
 * @package Morph
 * @author Jonathan Moss <jonathan.moss@tangentlabs.co.uk>
 * @copyright Jonathan Moss 2009
 */

/**
 * This class provides a few helper function for the Morph package
 *
 * @package Morph
 */
class Morph_Utils
{

    /**
     * Gets the default collection name for the passed in Morph_Object
     *
     * @param $object
     * @return string
     */
    public static function collectionName(Morph_Object $object)
    {
        return str_replace('_', '.', get_class($object));
    }

    /**
     * Generates an object reference for Has* properties
     *
     * @param $object
     * @return array
     */
    public static function objectReference(Morph_Object $object)
    {
        $reference = array(
            '$ref' => $object->collection(),
            '$id'  => $object->id()
        );

        //causes a fatal error in PHP at present. Will try again later
        //$reference = MorphDBRef::create($object->collection(), $object->id());

        return $reference;
    }
}
?>