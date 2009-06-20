<?php
require_once 'PHPUnit/Framework/TestCase.php';

require_once dirname(__FILE__).'/../src/Morph/Utils.php';

/**
 * @package Morph
 */
class TestUtils extends PHPUnit_Framework_TestCase
{

    public function testCollectionName()
    {
        $object = $this->getMock('Morph_Object', array(), array(), 'user_Profile');
        $collectionName = Morph_Utils::collectionName($object);
        $this->assertEquals('user.Profile', $collectionName);
    }

    public function testObjectReference()
    {
        $object = $this->getMock('Morph_Object', array('id', 'collection'));
        $object->expects($this->once())
               ->method('id')
               ->will($this->returnValue('anId'));
        $object->expects($this->once())
               ->method('collection')
               ->will($this->returnValue('aCollection'));
        $ref = Morph_Utils::objectReference($object);
        $expectedArray =  array(
            '$ref' => 'aCollection',
            '$id'  => 'anId'
        );

        $this->assertEquals($ref, $expectedArray);
    }

}
?>