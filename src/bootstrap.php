<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2010
 */
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
        'morph\\Collection'                  => 'phar://Morph/Collection.php',
        'morph\\Enum'                        => 'phar://Morph/Enum.php',
        'morph\\Utils'                       => 'phar://Morph/Utils.php',
        'morph\\Iterator'                    => 'phar://Morph/Iterator.php',
        'morph\\compare\\NumericProperty'    => 'phar://Morph/compare/NumericProperty.php',
        'morph\\compare\\Property'           => 'phar://Morph/compare/Property.php',
        'morph\\PropertySet'                 => 'phar://Morph/PropertySet.php',
        'morph\\Query'                       => 'phar://Morph/Query.php',
        'morph\\IQuery'                      => 'phar://Morph/IQuery.php',
        'morph\\Object'                      => 'phar://Morph/Object.php',
        'morph\\ICompare'                    => 'phar://Morph/ICompare.php',
        'morph\\Storage'                     => 'phar://Morph/Storage.php',
        'morph\\property\\HasMany'           => 'phar://Morph/property/HasMany.php',
        'morph\\property\\Date'              => 'phar://Morph/property/Date.php',
        'morph\\property\\ComposeMany'       => 'phar://Morph/property/ComposeMany.php',
        'morph\\property\\HasOne'            => 'phar://Morph/property/HasOne.php',
        'morph\\property\\Enum'              => 'phar://Morph/property/Enum.php',
        'morph\\property\\Integer'           => 'phar://Morph/property/Integer.php',
        'morph\\property\\File'              => 'phar://Morph/property/File.php',
        'morph\\property\\Float'             => 'phar://Morph/property/Float.php',
        'morph\\property\\ComposeOne'        => 'phar://Morph/property/ComposeOne.php',
        'morph\\property\\String'            => 'phar://Morph/property/String.php',
        'morph\\property\\Generic'           => 'phar://Morph/property/Generic.php',
        'morph\\query\\Property'             => 'phar://Morph/query/Property.php',
        'morph\\format\\Collection'          => 'phar://Morph/format/Collection.php',
        'morph\\exception\\ObjectNotFound'   => 'phar://Morph/exception/ObjectNotFound.php',
    );

    /**
     * class loader
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