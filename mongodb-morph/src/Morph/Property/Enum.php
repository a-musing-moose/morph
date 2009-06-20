<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <jonathan.moss@tangentlabs.co.uk>
 * @copyright Jonathan Moss 2009
 */

/**
 * Field object to represent an Enum property
 *
 * @package Morph
 * @subpackage Property
 */
class  Morph_Property_Enum extends Morph_Property_Generic
{

    /**
     * An array containing permitted values
     * @var array
     */
    private $Enums;

    /**
     *
     * @param string $name
     * @param mixed $default
     * @param array $enums An array of valid enum values
     * @return ar_Property_Enum
     */
    public function __construct($name, $default, array $enums)
    {
        $this->Enums = $enums;
        parent::__construct($name, $default);
    }

    /**
     * Overrides parent setValue to add enum value checking
     *
     * @see classes/ar/ar_Field#setValue()
     */
    public function setValue($value)
    {
        if($this->isValidEnum($value)){
            parent::setValue($value);
        }
    }

    /**
     * Returns a list of valid enum values
     *
     * @return array
     */
    public function getEnums()
    {
        return $this->Enums;
    }

    /**
     * Returns true only if $value is in the list of valid enums
     * @param $value
     * @return boolean
     */
    private function isValidEnum($value)
    {
        $isValidEnum = false;
        if(in_array($value, $this->Enums)){
            $isValidEnum = true;
        }
        return $isValidEnum;
    }

}
?>