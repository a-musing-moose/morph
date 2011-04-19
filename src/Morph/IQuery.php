<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2011 Jonathan Moss
 */

/**
 * An interface for query objects
 *
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 */
interface Morph_IQuery
{

    /**
     * @return array
     */
    function getRawQuery();

    /**
     * @return array
     */
    function getRawSort();

    /**
     * @return int
     */
    function getLimit();

    /**
     * @return int
     */
    function getSkip();

}
