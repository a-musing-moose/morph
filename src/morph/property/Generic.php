<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */
namespace morph\property;
/**
 * Class to represent a property within a table
 *
 * See Morph_Property_* for more specific implementations
 *
 * If you wish to create a more specialised property type you can extend
 * this class. Overloading getValue() and setValue() allow you to
 * provide automatic marshalling of data.
 *
 * @package Morph
 * @subpackage Property
 */
class Generic
{

    /**
     * The name of this property
     * @var string
     */
    protected $name;

    /**
     * The current value of this property
     * @var mixed
     */
    protected $value;

    /**
     * The current state of this property
     *
     * @see Morph_Enum::STATE_*
     * @var string
     */
    protected $state = \morph\Enum::STATE_NEW;

    /**
     * @param string $name
     * @param mixed  $default
     */
    public function __construct($name, $default = null)
    {
        $this->name = $name;
        $this->value = $default;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Returns the value associated with this property
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->__getRawValue();
    }

    /**
     * Sets the value of this property
     * @param $Value
     * @return void
     */
    public function setValue($value)
    {
        if ($this->state == \morph\Enum::STATE_CLEAN) {
            $this->state = \morph\Enum::STATE_DIRTY;
        }
        $this->__setRawValue($value);
    }

    /**
     * Returns the raw value of this property
     *
     * @final
     * @return mixed
     */
    public function __getRawValue()
    {
        return $this->value;
    }

    /**
     * Sets the raw value of this property
     *
     * @final
     * @param $value
     * @return Morph_Property_Generic
     */
    public function __setRawValue($value, $state = null)
    {
        $this->value = $value;
        if (null != $state) {
            $this->state = $state;
        }
        return $this;
    }

    /**
     * Returns the name associated with this property
     * @return string
     */
    public final function getName()
    {
        return $this->name;
    }

    /**
     * Returns a string representation of this property
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }
}
