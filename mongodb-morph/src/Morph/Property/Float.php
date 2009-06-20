<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

/**
 * Class to represent a float property
 *
 * @package Morph
 * @subpackage Property
 */
class Morph_Property_Float extends Morph_Property_Generic
{

    /**
     * Minimum value
     * @var float
     */
    protected $Minimum;

    /**
     * Maximum value
     * @var float
     */
    protected $Maximum;

    /**
     * @param string $name
     * @param float $default
     * @param float $minimum
     * @param float $maximum
     */
    public function __construct($name, $default = null, $minimum = null, $maximum = null){
        parent::__construct($name, $default);
        $this->Minimum = $minimum;
        $this->Maximum = $maximum;
    }

    /**
     * Sets the value of this attribute
     *
     * @param float $Value
     */
    public function setValue($value){
        $cleanValue = floatval($value);
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
     * @return float
     */
    public function getValue(){
        return (float)parent::getValue();
    }

}
?>