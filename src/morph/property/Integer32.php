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
class Integer32 extends Generic
{

    /**
     * The minimum allowed value
     * @var integer
     */
	protected $minimum;

	/**
	 * The maximum allowed value
	 * @var integer
	 */
	protected $maximum;

	/**
	 * @param string $Name
	 * @param integer $Default
	 * @param integer $Minimum
	 * @param integer $Maximum
	 */
	public function __construct($name, $default = null, $minimum = null, $maximum = null){
		parent::__construct($name, $default);
		$this->minimum = (\is_null($minimum))? null : (int)$minimum;
		$this->maximum = (\is_null($maximum))? null : (int)$maximum;
	}

	/**
	 * Sets the value of this attribute
	 *
	 * @param integer $Value
	 */
	public function setValue($value){
	    $cleanValue = (int)$value;
		if(!empty($this->minimum) && ($cleanValue < $this->minimum) ){
			//throw validation exception
			$cleanValue = $this->minimum;
		}
		if(!empty($this->maximum) && ($cleanValue > $this->maximum) ){
			//throw validation exception
			$cleanValue = $this->maximum;
		}
        parent::setValue(new \MongoInt32($cleanValue));
	}

	/**
	 * Returns this attributes value
	 *
	 * @return integer
	 */
	public function getValue(){
		return (int)parent::getValue()->value;
	}
}
