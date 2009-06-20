<?php
/**
 * @package Morph
 * @subpackage Compare
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

/**
 * Numeric comparator for Morph_Objects
 *
 * @package Morph
 * @subpackage Compare
 */
class  Morph_Compare_NumericProperty implements Morph_ICompare
{

    /**
     * Name of the property to compare
     * @var string
     */
    private $propertyName;

    /**
     *
     * @param string $propertyName The name of the property to compare
     * @return Morph_Compare_NumericProperty
     */
    public function __construct($propertyName)
    {
        $this->FieldName = $propertyName;
    }

    /**
     * The actual compare function
     *
     * @param Morph_Object $objectA
     * @param Morph_Object $objectB
     * @return integer
     */
    public function compare(Morph_Object $objectA, Morph_Object $objectB)
    {
        $compare = null;
        $propertyA = (float)$objectA->{$this->propertyName};
        $propertyB = (float)$objectB->{$this->propertyName};
        if ($propertyA == $propertyB){
           $compare = 0;
        }else{
           $compare = ($propertyA < $propertyB) ? -1 : 1;
        }
        return $compare;
    }

}
?>