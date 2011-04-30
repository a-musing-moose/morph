<?php
namespace morph\property;

require_once dirname(__FILE__).'/../../src/morph/property/Generic.php';
require_once dirname(__FILE__).'/../../src/morph/property/String.php';
require_once dirname(__FILE__).'/../../src/morph/property/Integer.php';
require_once dirname(__FILE__).'/../../src/morph/property/Float.php';
require_once dirname(__FILE__).'/../../src/morph/property/Enum.php';
require_once dirname(__FILE__).'/../../src/morph/property/Date.php';
require_once dirname(__FILE__).'/../../src/morph/property/BinaryData.php';
require_once dirname(__FILE__).'/../../src/morph/property/Integer32.php';
require_once dirname(__FILE__).'/../../src/morph/property/Integer64.php';
require_once dirname(__FILE__).'/../../src/morph/property/Regex.php';

/**
 * @package Morph
 * @subpackage Property
 */
class TestProperties extends \PHPUnit_Framework_TestCase
{

    public function testDefault()
    {
        $property = new Generic('TestProperty', 'TestValue');
        $this->assertEquals('TestValue', $property->getValue());
    }

    public function testName()
    {
        $property = new Generic('TestProperty', 'TestValue');
        $this->assertEquals('TestProperty', $property->getName());
    }

    public function testValue()
    {
        $property = new generic('TestProperty', 'TestValue');
        $property->setValue('AValue');
        $this->assertEquals('AValue', $property->getValue());
    }

    public function testStringProperty()
    {
        $validString = 'ABCDEFGHIJKLMNOP';
        $invalidString = 'ABCDEFGHIJKLMNOPQR';
        $property = new String('Name', null, 16);
        $property->setValue($validString);
        $this->assertEquals($validString, $property->getValue());
        $property->setValue($invalidString);
        $this->assertEquals(16, strlen($property->getValue()));
    }

    public function testIntegerProperty()
    {
        $min = 1;
        $max = 100;
        $validInteger = 5;
        $toLarge = 102;
        $toSmall = 0;
        $property = new Integer('AProperty', null, $min, $max);

        $property->setValue($validInteger);
        $this->assertEquals($validInteger, $property->getValue());

        $property->setValue($toLarge);
        $this->assertEquals($max, $property->getValue());

        $property->setValue($toSmall);
        $this->assertEquals($min, $property->getValue());
    }

    public function testFloatProperty()
    {
        $min = 1.0;
        $max = 100.00;
        $validFloat = 5.0;
        $toLarge = 100.1;
        $toSmall = 0.1;
        $property = new Float('AProperty', null, $min, $max);

        $property->setValue($validFloat);
        $this->assertEquals($validFloat, $property->getValue());

        $property->setValue($toLarge);
        $this->assertEquals($max, $property->getValue());

        $property->setValue($toSmall);
        $this->assertEquals($min, $property->getValue());
    }

    public function testEnumProperty()
    {
        $enums = array('Yes','No');
        $valid = 'Yes';
        $invalid = 'Bob';

        $property = new Enum('AProperty', $enums, $valid);

        $this->assertEquals($enums, $property->getChoices());

        $property->setValue($valid);
        $this->assertEquals($valid, $property->getValue());

        $property->setValue($invalid);
        $this->assertNotEquals($invalid, $property->getValue());
    }

    public function testDateProperty()
    {

        $property = new Date('AProperty');
        $time = time();

        //check it stores the value correctly
        $property->setValue($time);
        $this->assertEquals($time, $property->getValue());

        //check the output type is correct
        $this->assertInstanceOf('\MongoDate', $property->__getRawValue());

        //check the MorphDate objects content is correct
        $this->assertEquals($time, $property->__getRawValue()->sec);
    }
    
    public function testBinaryData()
    {
        $property = new BinaryData("AProperty");
        $data = md5('morph', true);
        $property->setValue($data);
        $this->assertEquals($data, $property->getValue());

        //check the output type is correct
        $this->assertInstanceOf('\MongoBinData', $property->__getRawValue());

        //check the MorphDate objects content is correct
        $this->assertEquals($data, $property->__getRawValue()->bin);
        
        $property2 = new BinaryData("AProperty");
        $property2->__setRawValue($property->__getRawValue());
        $this->assertEquals($data, $property2->getValue());
    }
    
	public function testInteger32Property()
    {
        $min = 1;
        $max = 100;
        $validInteger = 5;
        $toLarge = 102;
        $toSmall = 0;
        $property = new Integer32('AProperty', null, $min, $max);

        $property->setValue($validInteger);
        $this->assertEquals($validInteger, $property->getValue());

        $property->setValue($toLarge);
        $this->assertEquals($max, $property->getValue());

        $property->setValue($toSmall);
        $this->assertEquals($min, $property->getValue());
        
        $this->assertInstanceOf('\MongoInt32', $property->__getRawValue());
    }
    
	public function testInteger64Property()
    {
        $min = 1;
        $max = 100;
        $validInteger = 5;
        $toLarge = 102;
        $toSmall = 0;
        $property = new Integer64('AProperty', null, $min, $max);

        $property->setValue($validInteger);
        $this->assertEquals($validInteger, $property->getValue());

        $property->setValue($toLarge);
        $this->assertEquals($max, $property->getValue());

        $property->setValue($toSmall);
        $this->assertEquals($min, $property->getValue());
        
        $this->assertInstanceOf('\MongoInt64', $property->__getRawValue());
    }
    
    public function testRegex()
    {
        $property = new Regex("AProperty");
        $data = '/bob/';
        $property->setValue($data);
        $this->assertEquals($data, $property->getValue());
        $this->assertInstanceOf('\MongoRegex', $property->__getRawValue());
        $this->assertEquals($data, '/' . $property->__getRawValue()->regex . '/');
    }
    
}
