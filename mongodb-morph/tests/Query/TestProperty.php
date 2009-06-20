<?php
require_once 'PHPUnit/Framework/TestCase.php';

require_once dirname(__FILE__).'/../../src/Morph/Query/Property.php';

/**
 * Short summary of class
 *
 * Optional long description
 *
 * @package Morph
 * @subpackage Query
 */
class TestProperty  extends PHPUnit_Framework_TestCase
{


    public function testEquals()
    {
        $property = new Morph_Query_Property($this->getMockQuery());
        $property->equals('ABC');
        $this->assertEquals('ABC', $property->getConstraints());
    }

    public function testLessThan()
    {
        $expected = array('$lt' => 25);
        $property = new Morph_Query_Property($this->getMockQuery());
        $property->lessThan(25);
        $this->assertEquals($expected, $property->getConstraints());
    }

    public function testInvalidConstraintCombo()
    {
        $property = new Morph_Query_Property($this->getMockQuery());
        $property->equals('ABC');
        $this->setExpectedException('RuntimeException');
        $property->greaterThan(25);
    }

    public function testCombinationConstraints()
    {
        $expected = array('$lte' => 25, '$gte' => 10);
        $property = new Morph_Query_Property($this->getMockQuery());
        $property->lessThanOrEqualTo(25);
        $property->greaterThanOrEqualTo(10);
        $this->assertEquals($expected, $property->getConstraints());
    }

    public function testCombinationConstraintsFluent()
    {
        $expected = array('$lte' => 25, '$gte' => 10);
        $property = new Morph_Query_Property($this->getMockQuery());
        $property->lessThanOrEqualTo(25)
        ->greaterThanOrEqualTo(10);
        $this->assertEquals($expected, $property->getConstraints());
    }

    public function testNotEqualTo()
    {
        $expected = array('$ne' => 25);
        $property = new Morph_Query_Property($this->getMockQuery());
        $property->notEqualTo(25);
        $this->assertEquals($expected, $property->getConstraints());
    }

    public function testAll()
    {
        $expected = array('$all' => array('a', 'b', 'c'));
        $property = new Morph_Query_Property($this->getMockQuery());
        $property->all(array('a', 'b', 'c'));
        $this->assertEquals($expected, $property->getConstraints());
    }

    public function testIn()
    {
        $expected = array('$in' => array('a', 'b', 'c'));
        $property = new Morph_Query_Property($this->getMockQuery());
        $property->in(array('a', 'b', 'c'));
        $this->assertEquals($expected, $property->getConstraints());
    }

    public function testPropertyFluency()
    {
        $property = new Morph_Query_Property($this->getMockQuery(true));
        $newField = $property->property('bob');
        $this->assertTrue($newField instanceof Morph_Query_Property);
    }

    private function getMockQuery($willCallField = false)
    {
        $query = $this->getMock('Morph_Query', array('property'));
        if ($willCallField) {
            $query->expects($this->once())
            ->method('property')
            ->will($this->returnValue(new Morph_Query_Property($query)));
        }
        return $query;
    }

}
?>