<?php
/**
 * @package Morph
 * @subpackage Compare
 * @author Jonathan Moss <jonathan.moss@tangentlabs.co.uk>
 * @copyright Jonathan Moss 2009
 */

/**
 * Comparator for Morph_Object
 *
 * @package Morph
 * @subpackage Compare
 */
class  Morph_Compare_Property implements Morph_ICompare
{

    /**
     * Name of the property to compare
     * @var string
     */
    private $propertyName;

    /**
     *
     * @param string $propertyName The name of the property to compare
     * @return Morph_Compare_Property
     */
    public function __construct($propertyName)
    {
        $this->propertyName = $propertyName;
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
        if ($objectA->{$this->propertyName} == $objectB->{$this->propertyName}){
           $compare = 0;
        }else{
           $compare = ($objectA->{$this->propertyName} < $objectB->{$this->propertyName}) ? -1 : 1;
        }
        return $compare;
    }

}
?>