<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

/**
 * Interface for Morph comparators
 *
 * @package Morph
 */
interface Morph_ICompare
{
    /**
     * The actual comparison function
     *
     * The comparison function must return an integer less than, equal to, or greater than zero.
     * if the first argument is considered to be respectively less than, equal to, or greater than the second
     *
     * @param Morph_Object $objectA
     * @param Morph_Object $objectB
     * @return int
     */
    public function compare(Morph_Object $objectA, Morph_Object $objectB);

}
?>