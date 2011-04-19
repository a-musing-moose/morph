<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2011 Jonathan Moss
 */
namespace morph;
/**
 * An interface for query objects
 *
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 */
interface IQuery
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
