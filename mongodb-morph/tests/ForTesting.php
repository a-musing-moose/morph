<?php
/**
 * @package Morph
 * @author Jonathan Moss <jonathan.moss@tangentlabs.co.uk>
 * @copyright 2009 Tangent Labs
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