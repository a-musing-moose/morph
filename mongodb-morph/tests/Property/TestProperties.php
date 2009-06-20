<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once dirname(__FILE__).'/../../src/Morph/Property/Generic.php';
require_once dirname(__FILE__).'/../../src/Morph/Property/String.php';
require_once dirname(__FILE__).'/../../src/Morph/Property/Integer.php';
require_once dirname(__FILE__).'/../../src/Morph/Property/Float.php';
require_once dirname(__FILE__).'/../../src/Morph/Property/Enum.php';
require_once dirname(__FILE__).'/../../src/Morph/Property/Date.php';

/**
 * @package Morph
 * @subpackage Property
 */
class TestProperties extends PHPUnit_Framework_TestCase
{

    public function testDefault()
    {
        $property = new Morph_Property_Generic('TestProperty', 'TestValue');
        $this->assertEquals('TestValue', $property->getValue());
    }

    public function testName()
    {
        $property = new Morph_Property_Generic('TestProperty', 'TestValue');
        $this->assertEquals('TestProperty', $property->getName());
    }

    public function testValue()
    {
        $property = new Morph_Property_Generic('TestProperty', 'TestValue');
        $property->setValue('AValue');
        $this->assertEquals('AValue', $property->getValue());
    }

    public function testStringProperty()
    {
        $validString = 'ABCDEFGHIJKLMNOP';
        $invalidString = 'ABCDEFGHIJKLMNOPQR';
        $property = new Morph_Property_String('Name', null, 16);
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
        $property = new Morph_Property_Integer('AProperty', null, $min, $max);

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
        $property = new Morph_Property_Float('AProperty', null, $min, $max);

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

        $property = new Morph_Property_Enum('AProperty', $valid, $enums);

        $this->assertEquals($enums, $property->getEnums());

        $property->setValue($valid);
        $this->assertEquals($valid, $property->getValue());

        $property->setValue($invalid);
        $this->assertNotEquals($invalid, $property->getValue());
    }

    public function testDateProperty()
    {

        $property = new Morph_Property_Date('AProperty');
        $time = time();

        //check it stores the value correctly
        $property->setValue($time);
        $this->assertEquals($time, $property->getValue());

        //check the output type is correct
        $this->assertType('MongoDate', $property->__getRawValue());

        //check the MorphDate objects content is correct
        $this->assertEquals($time, $property->__getRawValue()->sec);
    }
}
?>