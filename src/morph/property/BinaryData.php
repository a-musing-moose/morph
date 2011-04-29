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
 * This property object is used to hold binary data
 * 
 * This is a wrapper around MongoBinData.  It will accept and string data
 * 
 *
 * @package Morph
 * @subpackage Property
 */
class BinaryData extends Generic
{

    /**
     * This is not really used as such at present
     * 
     * @var The binary type
     */
    protected $type;
    
    /**
     * @param type $name
     * @param type $type
     * @param type $default 
     */
    public function __construct($name, $type = \MongoBinData::BYTE_ARRAY, $default = null)
    {
        $this->type = $type;
        parent::__construct($name, $default);
    }
    
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
     * @param string $Value
     * @return void
     */
    public function setValue($value = null)
    {
        if (isset($value)) {
            $this->value = (string)$value;
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
        if (!isset($this->value)) return null;
        $rawValue = new \MongoBinData($this->value, $this->type);
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
        $this->value = $value->bin;
        return $this;
    }

}
