<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */
namespace morph\property;
/**
 * Class to represent an integer property
 *
 * @package Morph
 * @subpackage Property
 */
class Regex extends Generic
{


	/**
	 * @param string $name
	 */
	public function __construct($name)
	{
		parent::__construct($name, null);
	}

	/**
	 * Sets the value of this attribute
	 *
	 * @param string $value
	 */
	public function setValue($value)
	{
        parent::setValue(new \MongoRegex((string)$value));
	}

	/**
	 * Returns this attributes value
	 *
	 * @return integer
	 */
	public function getValue()
	{
		return '/' . (string)parent::getValue()->regex . '/';
	}
}
