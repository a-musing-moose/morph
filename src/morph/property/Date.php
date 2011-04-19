<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2009 Jonathan Moss
 * @version SVN: $Id$
 */
namespace morph\property;
/**
 * Short summary of class
 *
 * Optional long description
 *
 * @package Morph
 * @subpackage Property
 */
class Date extends Generic
{

    /**
     * Returns the date associated with this property as a timestamp
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of this property
     *
     * @param int $Value
     * @return void
     */
    public function setValue($value)
    {
        if (isset($value)) {
            $this->value = (int)$value;
        } else {
            $this->value = null;
        }
    }

    /**
     * Returns the raw value of this property
     *
     * @return mixed
     */
    public function __getRawValue()
    {
        if (! isset($this->value)) return null;
        $rawValue = new \MongoDate($this->value);
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
        if ($value instanceof \MongoDate) {
            $this->value = (int) $value->sec;
        } else {
            if (isset($value)) {
                $this->value = (int) $value;
            } else {
                $this->value = null;
            }
        }
        return $this;
    }

}
