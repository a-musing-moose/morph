<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2009 Jonathan Moss
 * @version SVN: $Id$
 */
require_once 'PHPUnit/Framework/TestCase.php';

require_once dirname(__FILE__).'/../src/Morph/PropertySet.php';
/**
 *
 * @package Morph
 */
class TestPropertySet extends PHPUnit_Framework_TestCase
{

    public function testAddingProperty()
    {
        $propertySet = new Morph_PropertySet();
        $property = $this->getMockProperty();
        $propertySet['AProperty'] = $property;
        $this->assertContains($property, $propertySet->getArrayCopy());
    }

    public function testAddingInvalidValue()
    {
        $propertySet = new Morph_PropertySet();
        $property = "I'm just wrong";
        $this->setExpectedException('InvalidArgumentException');
        $propertySet['AProperty'] = $property;
    }

    public function testAddingUnnamedProperty()
    {
        $propertySet = new Morph_PropertySet();
        $property = $this->getMockProperty();
        $this->setExpectedException('RuntimeException');
        $propertySet[] = $property;
    }

    public function testAppendProperty()
    {
        $propertySet = new Morph_PropertySet();
        $property = $this->getMockProperty();
        $this->setExpectedException('RuntimeException');
        $propertySet->append($property);
    }

    public function testAddingMultipleProperties()
    {
        $propertySet = new Morph_PropertySet();
        $property1 = $this->getMockProperty();
        $property2 = $this->getMockProperty();
        $property = $this->getMockProperty();
        $propertySet['AProperty'] = $property1;
        $propertySet['BProperty'] = $property2;
        $this->assertContains($property1, $propertySet->getArrayCopy());
        $this->assertContains($property2, $propertySet->getArrayCopy());
    }

    public function testGetPropertyValue()
    {
        $expected = 'AValue';
        $propertySet = new Morph_PropertySet();
        $propertySet['P1'] = $this->getMockPropertyForGet($expected);
        $this->assertEquals($expected, $propertySet->getPropertyValue('P1'));
    }

    public function testSetPropertyValue()
    {
        $expected = 'AValue';
        $propertySet = new Morph_PropertySet();
        $propertySet['P1'] = $this->getMockPropertyForSet($expected);
        $propertySet->setPropertyValue('P1', $expected);
    }

    public function testSetStorage()
    {
        $storage = $this->getMock('Morph_Storage');
        $propertySet = new Morph_PropertySet();
        $propertySet['P1'] = $this->getMockPropertyForSetStorage();
        $propertySet->setStorage($storage);
    }

    public function testGetRawPropertyValue()
    {
        $expected = 'AValue';
        $propertySet = new Morph_PropertySet();
        $propertySet['P1'] = $this->getMockPropertyFor__RawGet($expected);
        $this->assertEquals($expected, $propertySet->__getRawPropertyValue('P1'));
    }

    public function testSetRawPropertyValue()
    {
        $expected = 'AValue';
        $propertySet = new Morph_PropertySet();
        $propertySet['P1'] = $this->getMockPropertyFor__RawSet($expected);
        $propertySet->__setRawPropertyValue('P1', $expected);
    }

    // MOCK OBJECT FUNCTIONS

    private function getMockProperty()
    {
        return $this->getMock('Morph_Property_Generic', array(), array('name'));
    }

    private function getMockPropertyForGet($value)
    {
        $property = $this->getMock('Morph_Property_Generic', array('getValue'), array('name'));
        $property->expects($this->once())
        ->method('getValue')
        ->will($this->returnValue($value));
        return $property;
    }

    private function getMockPropertyForSet($value)
    {
        $property = $this->getMock('Morph_Property_Generic', array('setValue'), array('name'));
        $property->expects($this->once())
        ->method('setValue')
        ->with($this->equalTo($value));
        return $property;
    }

    private function getMockPropertyForSetStorage()
    {
        $property = $this->getMock('Morph_Property_Generic', array('setStorage'), array('name'));
        $property->expects($this->once())
        ->method('setStorage')
        ->with($this->isInstanceOf('Morph_Storage'));
        return $property;
    }

    private function getMockPropertyFor__RawGet($value)
    {
        $property = $this->getMock('Morph_Property_Generic', array('__getRawValue'), array('name'));
        $property->expects($this->once())
        ->method('__getRawValue')
        ->will($this->returnValue($value));
        return $property;
    }

    private function getMockPropertyFor__RawSet($value)
    {
        $property = $this->getMock('Morph_Property_Generic', array('__setRawValue'), array('name'));
        $property->expects($this->once())
        ->method('__setRawValue')
        ->with($this->equalTo($value));
        return $property;
    }

}
?>