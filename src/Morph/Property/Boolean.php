<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

/**
 * Class to represent an boolean property
 *
 * @package Morph
 * @subpackage Property
 */
class Morph_Property_Boolean extends Morph_Property_Generic
{

    /**
     * The minimum allowed value
     * @var integer
     */
	protected $Minimum;

	/**
	 * The maximum allowed value
	 * @var integer
	 */
	protected $Maximum;

	/**
	 * @param string $name
	 * @param boolean $default
	 */
	public function __construct($name, $default = null){
		if (!is_null($default)) {
			$default = (boolean)$default;
		}
		parent::__construct($name, $default);
	}

	/**
	 * Sets the value of this attribute
	 *
	 * @param boolean $Value
	 */
	public function setValue($value){
	    $cleanValue = (boolean)$value;
        parent::setValue($cleanValue);
	}

	/**
	 * Returns this attributes value
	 *
	 * @return boolean
	 */
	public function getValue(){
		return (boolean)parent::getValue();
	}

}
