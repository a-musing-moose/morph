<?php
namespace morph;

require_once dirname(__FILE__).'/../src/morph/Enum.php';
require_once dirname(__FILE__).'/../src/morph/Object.php';
require_once dirname(__FILE__).'/../src/morph/PropertySet.php';
require_once dirname(__FILE__).'/../src/morph/Utils.php';
require_once dirname(__FILE__).'/../src/morph/property/Generic.php';
require_once dirname(__FILE__).'/ForTesting.php';

/**
 * @package Morph
 */
class TestObject extends \PHPUnit_Framework_TestCase
{


    public function testGetCollection()
    {
        $obj = new ForTesting();
        $this->assertEquals('morph.ForTesting', $obj->collection());
    }

    public function testCollectionOverride()
    {
        $obj = new ForTesting();
        $collectionName = "TestCollection";
        $obj->collection($collectionName);
        $this->assertEquals($collectionName, $obj->collection());
    }

    public function testMagicGettersSetter()
    {
        $testValue = 'TestValue';
        $obj = new ForTesting();
        $obj->testField = $testValue;
        $this->assertEquals($testValue, $obj->testField);
    }

    public function testSetData()
    {
        $data = array('testField' => 'value1');
        $obj = new ForTesting();
        $obj->__setData($data);
        $this->assertEquals($data['testField'], $obj->testField);
        $this->assertEquals(Enum::STATE_DIRTY, $obj->state());

    }

    public function testGetData()
    {
        $data = array(
            '_ns'           => 'morph\ForTesting',
            'testField'     => 'value1',
            'instanceOf'    => 'Morph_ForTesting'
        );
        $obj = new ForTesting();
        $obj->__setData($data);
        $this->assertEquals($data, $obj->__getData());
        $this->assertEquals(Enum::STATE_DIRTY, $obj->state());
    }

    public function test__toArray()
    {
        $testValue = 'TestValue';
        $expectedArray = array(
            'testField' => 'TestValue'
        );
        $obj = new ForTesting();
        $obj->testField = $testValue;
        $this->assertEquals($expectedArray, $obj->__toArray());
    }

    public function test__toString()
    {
        $expected = "Id: \nState: New\ntestField: TEST\n";
        $obj = new ForTesting();
        $obj->testField = 'TEST';
        $this->assertEquals($expected, $obj->__toString());
    }

}
?>