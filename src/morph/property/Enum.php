<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */
namespace morph\property;
/**
 * Field object to represent an Enum property
 *
 * @package Morph
 * @subpackage Property
 */
class Enum extends Generic
{

    /**
     * An array containing permitted values
     * @var array
     */
    private $choices;

    /**
     *
     * @param string $name
     * @param mixed $default
     * @param array $enums An array of valid enum values
     * @return ar_Property_Enum
     */
    public function __construct($name, $default, array $choices)
    {
        $this->choices = $choices;
        parent::__construct($name, $default);
    }

    /**
     * Overrides parent setValue to add enum value checking
     *
     * @see classes/ar/ar_Field#setValue()
     */
    public function setValue($value)
    {
        if($this->isValidChoice($value)){
            parent::setValue($value);
        }
    }

    /**
     * Returns a list of valid enum values
     *
     * @return array
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Returns true only if $value is in the list of valid enums
     * @param $value
     * @return boolean
     */
    private function isValidChoice($value)
    {
        $isValidEnum = false;
        if(in_array($value, $this->getChoices())){
            $isValidEnum = true;
        }
        return $isValidEnum;
    }

}
