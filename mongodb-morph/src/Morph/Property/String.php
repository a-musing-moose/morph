<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

/**
 * Class to represent a string property
 *
 * @package Morph
 * @subpackage Property
 */
class Morph_Property_String extends Morph_Property_Generic
{

    /**
     * The maximum string length permitted
     * @var integer
     */
    protected $MaximumLength;

    /**
     * @param string $Name
     * @param string $Default
     * @param integer $MaximumLength
     */
    public function __construct($name, $default = null, $maximumLength = null){
        parent::__construct($name, $default);
        $this->MaximumLength = $maximumLength;
    }

    /**
     * Sets the value of this attribute
     *
     * @param integer $Value
     */
    public function setValue($value){
        $cleanValue = strval($value);
        if(!empty($this->MaximumLength) && (strlen($cleanValue) > $this->MaximumLength) ){
            //throw validation exception
            $cleanValue = substr($cleanValue,0,$this->MaximumLength);
        }
        parent::setValue($cleanValue);
    }

    /**
     * Returns this attributes value
     *
     * @return integer
     */
    public function getValue(){
        return (string)parent::getValue();
    }

}
?>