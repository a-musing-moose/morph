<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */
namespace morph\property;
/**
 * Class to represent a float property
 *
 * @package Morph
 * @subpackage Property
 */
class Float extends Generic
{

    /**
     * Minimum value
     * @var float
     */
    protected $minimum;

    /**
     * Maximum value
     * @var float
     */
    protected $maximum;

    /**
     * @param string $name
     * @param float $default
     * @param float $minimum
     * @param float $maximum
     */
    public function __construct($name, $default = null, $minimum = null, $maximum = null){
        parent::__construct($name, $default);
        $this->minimum = (\is_null($minimum))? null : (float)$minimum;
        $this->maximum = (\is_null($maximum))? null : (float)$maximum;
    }

    /**
     * Sets the value of this attribute
     *
     * @param float $Value
     */
    public function setValue($value){
        $cleanValue = (float)$value;
        if(!empty($this->minimum) && ($cleanValue < $this->minimum) ){
            //throw validation exception
            $cleanValue = $this->minimum;
        }
        if(!empty($this->maximum) && ($cleanValue > $this->maximum) ){
            //throw validation exception
            $cleanValue = $this->maximum;
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
