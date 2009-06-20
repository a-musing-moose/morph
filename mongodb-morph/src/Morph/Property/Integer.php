<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

/**
 * Class to represent an integer property
 *
 * @package Morph
 * @subpackage Property
 */
class Morph_Property_Integer extends Morph_Property_Generic
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
	 * @param string $Name
	 * @param integer $Default
	 * @param integer $Minimum
	 * @param integer $Maximum
	 */
	public function __construct($name, $default = null, $minimum = null, $maximum = null){
		parent::__construct($name, $default);
		$this->Minimum = $minimum;
		$this->Maximum = $maximum;
	}

	/**
	 * Sets the value of this attribute
	 *
	 * @param integer $Value
	 */
	public function setValue($value){
	    $cleanValue = intval($value);
		if(!empty($this->Minimum) && ($cleanValue < $this->Minimum) ){
			//throw validation exception
			$cleanValue = $this->Minimum;
		}
		if(!empty($this->Maximum) && ($cleanValue > $this->Maximum) ){
			//throw validation exception
			$cleanValue = $this->Maximum;
		}
        parent::setValue($cleanValue);
	}

	/**
	 * Returns this attributes value
	 *
	 * @return integer
	 */
	public function getValue(){
		return (int)parent::getValue();
	}

}
?>