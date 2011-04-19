<?php
namespace morph;
require_once dirname(__FILE__).'/../src/morph/PropertySet.php';
require_once dirname(__FILE__).'/../src/morph/Enum.php';
require_once dirname(__FILE__).'/../src/morph/Object.php';
require_once dirname(__FILE__).'/../src/morph/Utils.php';
/**
 * @package Morph
 */
class TestUtils extends \PHPUnit_Framework_TestCase
{

    public function testCollectionName()
    {
        $object = $this->getMock('\morph\Object', array(), array());
        $className = str_replace("_", ".", \get_class($object));
        $collectionName = Utils::collectionName($object);
        $this->assertEquals($className, $collectionName);
    }

    public function testObjectReference()
    {
        $object = $this->getMock('\morph\Object', array('id', 'collection'));
        $object->expects($this->once())
               ->method('id')
               ->will($this->returnValue('anId'));
        $object->expects($this->once())
               ->method('collection')
               ->will($this->returnValue('aCollection'));
        $ref = Utils::objectReference($object);
        $expectedArray =  array(
            '$ref' => 'aCollection',
            '$id'  => 'anId'
        );

        $this->assertEquals($ref, $expectedArray);
    }

}