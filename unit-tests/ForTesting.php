<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2009 Jonathan Moss
 * @version SVN: $Id$
 */
namespace morph;
/**
 * Short summary of class
 *
 * Optional long description
 *
 * @package Morph
 */
class ForTesting extends Object
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new \morph\property\Generic('testField'));
    }

}