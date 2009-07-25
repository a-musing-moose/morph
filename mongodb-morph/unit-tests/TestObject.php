<?php
require_once 'PHPUnit/Framework/TestCase.php';


require_once dirname(__FILE__).'/../src/Morph/Object.php';
require_once dirname(__FILE__).'/../src/Morph/PropertySet.php';
require_once dirname(__FILE__).'/../src/Morph/Utils.php';
require_once dirname(__FILE__).'/../src/Morph/Property/Generic.php';
require_once dirname(__FILE__).'/ForTesting.php';

/**
 * @package Morph
 */
class TestObject extends PHPUnit_Framework_TestCase
{


    public function testGetCollection()
    {
        $obj = new Morph_ForTesting();
        $this->assertEquals('Morph.ForTesting', $obj->collection());
    }

    public function testCollectionOverride()
    {
        $obj = new Morph_ForTesting();
        $collectionName = "TestCollection";
        $obj->collection($collectionName);
        $this->assertEquals($collectionName, $obj->collection());
    }

    public function testMagicGettersSetter()
    {
        $testValue = 'TestValue';
        $obj = new Morph_ForTesting();
        $obj->TestField = $testValue;
        $this->assertEquals($testValue, $obj->TestField);
    }

    public function testSetData()
    {
        $data = array('TestField' => 'value1');
        $obj = new Morph_ForTesting();
        $obj->__setData($data);
        $this->assertEquals($data['TestField'], $obj->TestField);
        $this->assertEquals(Morph_Object::STATE_DIRTY, $obj->state());

    }

    public function testGetData()
    {
        $data = array(
            'TestField'     => 'value1',
            'instanceOf'    => 'Morph_ForTesting'
        );
        $obj = new Morph_ForTesting();
        $obj->__setData($data);
        $this->assertEquals($data, $obj->__getData());
        $this->assertEquals(Morph_Object::STATE_DIRTY, $obj->state());
    }

    public function test__toArray()
    {
        $testValue = 'TestValue';
        $expectedArray = array(
        'TestField' => 'TestValue'
        );
        $obj = new Morph_ForTesting();
        $obj->TestField = $testValue;
        $this->assertEquals($expectedArray, $obj->__toArray());
    }

    public function test__toString()
    {
        $expected = "Id: \nState: New\nTestField: TEST\n";
        $obj = new Morph_ForTesting();
        $obj->TestField = 'TEST';
        $this->assertEquals($expected, $obj->__toString());
    }

}
?>