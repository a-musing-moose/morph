<?php
/**
 * @package Morph
 * @subpackage Property
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */
namespace morph\property;

/**
 * Class to represent a string property
 *
 * @package Morph
 * @subpackage Property
 */
class String extends Generic
{
    /**
     * The maximum string length permitted
     * @var integer
     */
    protected $maximumLength;

    /**
     * @param string  $name
     * @param string  $default
     * @param integer $maximumLength
     */
    public function __construct($name, $default = null, $maximumLength = null)
    {
        parent::__construct($name, $default);
        $this->maximumLength = (is_null($maximumLength)) ? null : (int)$maximumLength;
    }

    /**
     * Sets the value of this attribute
     *
     * @param  mixed $value
     * @return void
     */
    public function setValue($value)
    {
        if (null === $value) {
            return parent::setValue(null);
        }

        $cleanValue = (string)$value;
        if(!empty($this->maximumLength)
            && ($this->_getUnicodeValue('strlen', $cleanValue) > $this->maximumLength) ){
            $cleanValue = $this->_getUnicodeValue(
                'substr', array($cleanValue, 0, $this->maximumLength)
            );
        }

        parent::setValue($cleanValue);
    }

    /**
     * Returns this attributes value
     *
     * @return string
     */
    public function getValue()
    {
        return (null === parent::getValue()) ? null : (string)parent::getValue();
    }

    /**
    * Gets unicode value according function name
    *
    * @param  string $fncName
    * @param  mixed  $value
    * @return string
    */
    protected function _getUnicodeValue($fncName, $value)
    {
        // PHP 5.4 has native support of unicode chars
        if (version_compare(PHP_VERSION, '5.4', '>=')) {
            return call_user_func_array($fncName, (array)$value);
        }

        // iconv or mbstring
        $prefix = 'iconv';
        if (extension_loaded('mbstring')) {
            $prefix = 'mb';
        }

        return call_user_func_array($prefix . '_' . $fncName, (array)$value);
    }
}
