<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2009 Jonathan Moss
 * @version SVN: $Id$
 */

/**
 * Short summary of class
 *
 * Optional long description
 *
 * @package Morph
 */
class Morph_ForTesting extends Morph_Object
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new Morph_Property_Generic('TestField'));
    }

}
?>