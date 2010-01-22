<?php
Phar::mapPhar();

/**
 * Autoloader for the morph package
 *
 */
class MorphAutoloader
{

    /**
     * A static array of classes
     *
     * @var array
     */
    private static $classes = array(
        'Morph_Utils'                   => 'phar://Morph/Utils.php',
        'Morph_Iterator'                => 'phar://Morph/Iterator.php',
        'Morph_Compare_NumericProperty' => 'phar://Morph/Compare/NumericProperty.php',
        'Morph_Compare_Property'        => 'phar://Morph/Compare/Property.php',
        'Morph_Query'                   => 'phar://Morph/Query.php',
        'Morph_Object'                  => 'phar://Morph/Object.php',
        'Morph_ICompare'                => 'phar://Morph/ICompare.php',
        'Morph_Property_HasMany'        => 'phar://Morph/Property/HasMany.php',
        'Morph_Property_Date'           => 'phar://Morph/Property/Date.php',
        'Morph_Property_ComposeMany'    => 'phar://Morph/Property/ComposeMany.php',
        'Morph_Property_HasOne'         => 'phar://Morph/Property/HasOne.php',
        'Morph_Property_Enum'           => 'phar://Morph/Property/Enum.php',
        'Morph_Property_Integer'        => 'phar://Morph/Property/Integer.php',
        'Morph_Property_File'           => 'phar://Morph/Property/File.php',
        'Morph_Property_Float'          => 'phar://Morph/Property/Float.php',
        'Morph_Property_ComposeOne'     => 'phar://Morph/Property/ComposeOne.php',
        'Morph_Property_String'         => 'phar://Morph/Property/String.php',
        'Morph_Property_Generic'        => 'phar://Morph/Property/Generic.php',
        'Morph_Collection'              => 'phar://Morph/Collection.php',
        'Morph_Query_Property'          => 'phar://Morph/Query/Property.php',
        'Morph_PropertySet'             => 'phar://Morph/PropertySet.php',
        'Morph_Storage'                 => 'phar://Morph/Storage.php',
        'Morph_Format_Collection'       => 'phar://Morph/Format/Collection.php'
    );

    /**
     * autoload function
     *
     * @param string $className
     * @return boolean
     */
    public static function load($className)
    {
        $isLoaded = false;
        if (isset(self::$classes[$className])) {
            include self::$classes[$className];
            $isLoaded = true;
        }
        return $isLoaded;
    }

}

//register the autoloader
spl_autoload_register(array('MorphAutoloader', 'load'));

__HALT_COMPILER();