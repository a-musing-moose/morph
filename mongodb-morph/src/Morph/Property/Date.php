<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2009 Jonathan Moss
 * @version SVN: $Id$
 */

/**
 * Short summary of class
 *
 * Optional long description
 *
 * @package Morph
 * @subpackage Property
 */
class Morph_Property_Date extends Morph_Property_Generic
{


    /**
     *
     * @param string $name
     * @param mixed $default
     * @return Morph_Property_Date
     */
    public function __construct($name, $default = null)
    {
        $this->Name = $name;
        $this->Value = $default;
    }

    /**
     * Returns the date associated with this property as a timestamp
     *
     * @return int
     */
    public function getValue()
    {
        return $this->Value;
    }

    /**
     * Sets the value of this property
     *
     * @param int $Value
     * @return void
     */
    public function setValue($value)
    {
        $this->Value = (int) $value;
    }

    /**
     * Returns the raw value of this property
     *
     * @return mixed
     */
    public function __getRawValue()
    {
        $rawValue = new MongoDate($this->Value);
        return $rawValue;
    }

    /**
     * Sets the raw value of this property
     *
     * @param $value
     * @return Morph_Property_Generic
     */
    public function __setRawValue($value)
    {
        if ($value instanceof MongoDate) {
            $this->Value = (int) $value->sec;
        } else {
            $this->Value = (int) $value;
        }

        return $this;
    }

}
